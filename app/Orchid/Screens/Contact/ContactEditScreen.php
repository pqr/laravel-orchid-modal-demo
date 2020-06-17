<?php


namespace App\Orchid\Screens\Contact;


use App\Models\Contact;
use App\Models\Phone;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Toast;

class ContactEditScreen extends Screen
{
    public $name = 'Contact Edit';
    public $exists = false;

    public function query(Contact $concact): array
    {
        $this->exists = $concact->exists;
        if ($this->exists) {
            $this->name = 'Edit Model';
        }

        $concact->load('phones');

        return [
            'contact' => $concact,
            'phones' => $concact->phones,
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
                ->method('savePhone')
                ->canSee($this->exists),

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
            Layout::rows(
                [
                    Input::make('contact.name')->title('Contact Name')->required(),
                ]
            ),
            Layout::table(
                'contact.phones',
                [
                    TD::set('description'),
                    TD::set('number'),
                    TD::set('actions')->render(
                        static function (Phone $phone) {
                            return
                                ModalToggle::make('Edit Phone')
                                    ->icon('icon-pencil')
                                    ->modal('phoneModal')
                                    ->modalTitle('Edit Phone')
                                    ->method('savePhone')
                                    /**
                                     * Чтобы загрузить информацию о телефоне в методе asyncGetPhone
                                     * туда нужно передать как минимум $phone->id
                                     *
                                     * Однако, поскольку мы уже находимся в route
                                     * contacts/{contact}/edit/{method?}/{argument?}
                                     * то асинхронный запрос будет отправлен именно на этот route
                                     * и нам нужно предоставить обязательный параметр {contact}
                                     *
                                     * При нажатии Apply на этом модальном окне ожидаем, что будет
                                     * отправлен ajax запрос на url:
                                     *  /contacts/<id контакта>/savePhone?phoneId=1
                                     *
                                     * На деле запрос отправляется по адресу: /contacts/<id контакта>/edit?phoneId=1/savePhone
                                     * что вызывает ошибку
                                     *
                                     * BadMethodCallException
                                     * Method App\Orchid\Screens\Contact\ContactEditScreen::1 does not exist.
                                     */
                                    ->asyncParameters(
                                        [
                                            'contact' => $phone->contact_id,
                                            'phoneId' => $phone->id,
                                        ]
                                    )
                                .
                                Button::make(__('Remove'))
                                    ->method('removePhone')
                                    ->confirm(
                                        __('Are you sure you want to remove phone ' . $phone->description . '?')
                                    )
                                    ->parameters(
                                        [
                                            'phoneId' => $phone->id,
                                        ]
                                    )
                                    ->icon('icon-trash');
                        }
                    ),

                ]
            ),

            Layout::modal(
                'phoneModal',
                [
                    Layout::rows(
                        [
                            Input::make('phone.id')->hidden(),
                            Input::make('phone.description')
                                ->required()
                                ->title('Description')
                                ->placeholder('Work/Personal/Mobile/etc...'),
                            Input::make('phone.number')
                                ->required()
                                ->title('Number'),
                        ]
                    ),
                ],
            )->async('asyncGetPhone'),
        ];
    }

    /**
     * TODO: можно ли здесь как-то использовать model binding и получить Phone $phone в качестве параметра?
     * Подозреваю что нет, т.к. phoneId не является route параметром
     *
     * @param Request $request
     * @return Phone[]|array
     */
    public function asyncGetPhone(Request $request): array
    {
        $phoneId = $request->get('phoneId');
        if ($phoneId) {
            // Пытаемся загрузить имеющийся Phone для модального окна редактирования
            $phone = Phone::findOrFail($phoneId);
        } else {
            // Это модальное окно создания нового телефона
            $phone = new Phone();
        }

        return [
            'phone' => $phone,
        ];
    }

    public function save(Contact $contact, Request $request)
    {
        $contact->fill($request->get('contact'))->save();

        Toast::info(__('Contact saved'));

        return redirect()->route('contacts');
    }

    /**
     * TODO: можно ли здесь как-то использовать model binding и получить Phone $phone в качестве параметра?
     * Подозреваю что нет, т.к. phoneId не является route параметром
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function savePhone(Contact $contact, Request $request)
    {
        $phoneData = $request->get('phone');
        if (!empty($phoneData['id'])) {
            // Сохраняем телефон из модального окна редактирования (т.е. этот телефон уже есть в базе и его нужно обвноить)
            $phone = Phone::findOrFail($phoneData['id']);
        } else {
            // Это было модальное окно создания нового телефона
            $phone = new Phone();
            // Не забудем новый телефон сразу привязать к карточке контакта
            $phone->contact_id = $contact->id;
        }

        // Заполняем данными пришедшими из формы из модального окна
        $phone->fill($phoneData);
        $phone->save();

        Toast::info(__('Phone saved'));

        return back();
    }

    /**
     * TODO: можно ли здесь как-то использовать model binding и получить Phone $phone в качестве параметра?
     * Подозреваю что нет, т.к. phoneId не является route параметром
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removePhone(Request $request)
    {
        $phoneId = $request->get('phoneId');
        $phone = Phone::findOrFail($phoneId);
        $phone->delete();

        Toast::info(__('Phone removed'));

        return back();
    }
}
