<?php

namespace App\Orchid\Screens\Contact;

use App\Models\Contact;
use App\Orchid\Layouts\Contact\ContactDescriptionRow;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Toast;

class ContactListScreen extends Screen
{
    public $name = 'Contacts';

    public function query(): array
    {
        return [
            'contacts' => Contact::paginate(),
        ];
    }

    public function commandBar(): array
    {
        return [
            ModalToggle::make('Create Contact')
                ->icon('icon-plus')
                ->modal('create')
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
            Layout::table('contacts', [
                TD::set('label', 'Name')
                    ->cantHide()
                    ->render(function (Contact $contact){
                        return Link::make($contact->name)->route('contacts.edit', $contact->id);
                    })
            ]),

            Layout::modal('create', [ContactDescriptionRow::class])
                ->title('Create new contact'),
        ];
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {
        Contact::create($request->get('contact'));

        Toast::info(__('Contact saved'));

        return back();
    }
}
