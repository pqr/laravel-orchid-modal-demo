<?php


namespace App\Orchid\Screens\Contact;


use App\Models\Contact;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;

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
            Link::make('Create New')
                ->icon('icon-plus')
                ->href(route('contacts.create')),
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

            Layout::table(
                'contacts',
                [
                    TD::set('label', 'Name')
                        ->render(
                            function ($model) {
                                return Link::make($model->name)
                                    ->route('contacts.edit', $model->id);
                            }
                        ),
                ]
            ),
        ];
    }
}
