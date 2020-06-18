<?php


namespace App\Orchid\Screens\Contact;


use App\Models\Contact;
use App\Models\Phone;
use App\Orchid\Layouts\Contact\ContactDescriptionRow;
use App\Orchid\Layouts\Contact\ContactPhoneTable;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ContactEditScreen extends Screen
{
    public $name = 'Contact Edit';

    /**
     * @param Contact $concact
     *
     * @return Contact[]|array
     */
    public function query(Contact $concact): array
    {
        $this->name = 'Edit Model';

        $concact->load('phones');

        return [
            'contact' => $concact,
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [
            ModalToggle::make('Add Phone')
                ->icon('icon-plus')
                ->modal('phoneModal')
                ->modalTitle('Add New Phone To This Contact')
                ->method('savePhone'),

            Button::make('Save')
                ->icon('icon-check')
                ->method('save'),
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            ContactDescriptionRow::class,
            ContactPhoneTable::class,

            Layout::modal('phoneModal', [
                Layout::rows([
                    Input::make('phone.description')
                        ->required()
                        ->title('Description')
                        ->placeholder('Work/Personal/Mobile/etc...'),
                    Input::make('phone.number')
                        ->required()
                        ->title('Number'),
                ]),
            ])->async('asyncGetPhone'),
        ];
    }

    /**
     * @param Contact $contact
     * @param Phone   $phone
     *
     * @return Phone[]|array
     */
    public function asyncGetPhone(Contact $contact, Phone $phone): array
    {
        return [
            'phone' => $phone,
        ];
    }

    /**
     * @param Contact $contact
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Contact $contact, Request $request)
    {
        $contact->fill($request->get('contact'))->save();

        Toast::info(__('Contact saved'));

        return back();
    }

    /**
     * @param Contact|null $contact
     * @param Phone        $phone
     * @param Request      $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function savePhone(Contact $contact, Phone $phone, Request $request)
    {
        $phone
            ->findOrNew($request->get('phoneId'))
            ->fill($request->get('phone'))
            ->fill([
                'contact_id' => $contact->id,
            ])
            ->save();

        Toast::info(__('Phone saved'));

        return back();
    }

    /**
     * TODO: можно ли здесь как-то использовать model binding и получить Phone $phone в качестве параметра?
     * Подозреваю что нет, т.к. phoneId не является route параметром
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removePhone(Request $request)
    {
        $phone = Phone::findOrFail($request->get('phoneId'));
        $phone->delete();

        Toast::info(__('Phone removed'));

        return back();
    }
}
