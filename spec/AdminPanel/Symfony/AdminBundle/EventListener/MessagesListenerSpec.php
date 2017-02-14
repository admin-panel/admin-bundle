<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\EventListener;

use AdminPanel\Symfony\AdminBundle\Event\BatchEvents;
use AdminPanel\Symfony\AdminBundle\Event\FormEvent;
use AdminPanel\Symfony\AdminBundle\Event\FormEvents;
use AdminPanel\Symfony\AdminBundle\Message\FlashMessages;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\Form;

class MessagesListenerSpec extends ObjectBehavior
{
    public function let(FlashMessages $flashMessages)
    {
        $this->beConstructedWith($flashMessages);
    }

    public function it_listen_events()
    {
        $this->getSubscribedEvents()->shouldReturn([
            FormEvents::FORM_REQUEST_POST_SUBMIT => 'onFormRequestPostSubmit',
            FormEvents::FORM_DATA_POST_SAVE => 'onFormDataPostSave',
            BatchEvents::BATCH_OBJECTS_POST_APPLY => 'onBatchObjectsPostApply',
        ]);
    }

    public function it_not_set_error_message_when_form_is_valid(
        FormEvent $event,
        Form $form,
        FlashMessages $flashMessages
    ) {
        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $flashMessages->error('messages.form.error')->shouldNotBeCalled();

        $this->onFormRequestPostSubmit($event);
    }

    public function it_set_error_message_when_form_is_invalid(
        FormEvent $event,
        Form $form,
        FlashMessages $flashMessages
    ) {
        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(false);
        $flashMessages->error('messages.form.error')->shouldBeCalled();

        $this->onFormRequestPostSubmit($event);
    }

    public function it_add_message_on_post_save(FormEvent $event, FlashMessages $flashMessages)
    {
        $flashMessages->success('messages.form.save')->shouldBeCalled();
        $this->onFormDataPostSave($event);
    }

    public function it_add_message_on_batch_post_apply(FormEvent $event, FlashMessages $flashMessages)
    {
        $flashMessages->success('messages.batch.success')->shouldBeCalled();
        $this->onBatchObjectsPostApply($event);
    }
}
