<?php

namespace App\Orchid\Layouts\Contact;

use App\Models\Phone;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Repository;
use Orchid\Screen\TD;

class ContactPhoneTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'contact.phones';

    /**
     * @param Repository $query
     *
     * @return bool
     */
    public function canSee(Repository $query): bool
    {
        return $query->get('contact.phones')->isNotEmpty();
    }

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::set('description'),

            TD::set('number')
                ->cantHide(),

            TD::set('actions')
                ->align(TD::ALIGN_RIGHT)
                ->cantHide()
                ->render(static function (Phone $phone) {
                    return DropDown::make()
                        ->icon('icon-options-vertical')
                        ->list([
                            ModalToggle::make('Edit Phone')
                                ->icon('icon-pencil')
                                ->modal('phoneModal')
                                ->modalTitle('Edit Phone')
                                ->method('savePhone')
                                ->asyncParameters([
                                    'contact' => $phone->contact_id,
                                    'phoneId' => $phone->id,
                                ])
                                ->addBeforeRender(function () use ($phone) {
                                    /* HACK! */
                                    $this->set('action', route(\Route::currentRouteName(), [
                                        'contact' => $phone->contact_id,
                                        'phoneId' => $phone->id,
                                        'method'  => $this->get('method'),
                                    ]));
                                }),


                            Button::make(__('Remove'))
                                ->icon('icon-trash')
                                ->method('removePhone')
                                ->confirm(
                                    __('Are you sure you want to remove phone ' . $phone->description . '?')
                                )
                                ->parameters([
                                    'contactId' => $phone->contact_id,
                                    'phoneId'   => $phone->id,
                                ]),

                        ]);
                }),
        ];
    }
}
