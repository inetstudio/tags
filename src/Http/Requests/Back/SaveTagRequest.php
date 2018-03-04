<?php

namespace InetStudio\Tags\Http\Requests\Back;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use InetStudio\Uploads\Validation\Rules\CropSize;
use InetStudio\Tags\Contracts\Http\Requests\Back\SaveTagRequestContract;

/**
 * Class SaveTagRequest.
 */
class SaveTagRequest extends FormRequest implements SaveTagRequestContract
{
    /**
     * Определить, авторизован ли пользователь для этого запроса.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Сообщения об ошибках.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'meta.title.max' => 'Поле «Title» не должно превышать 255 символов',
            'meta.description.max' => 'Поле «Description» не должно превышать 255 символов',
            'meta.keywords.max' => 'Поле «Keywords» не должно превышать 255 символов',

            'meta.og:title.max' => 'Поле «og:itle» не должно превышать 255 символов',
            'meta.og:description.max' => 'Поле «og:description» не должно превышать 255 символов',

            'og_image.crop.default.json' => 'Область отображения должна быть представлена в виде JSON',

            'name.required' => 'Поле «Название» обязательно для заполнения',
            'name.max' => 'Поле «Название» не должно превышать 255 символов',

            'slug.required' => 'Поле «URL» обязательно для заполнения',
            'slug.alpha_dash' => 'Поле «URL» может содержать только латинские символы, цифры, дефисы и подчеркивания',
            'slug.max' => 'Поле «URL» не должно превышать 255 символов',
            'slug.unique' => 'Такое значение поля «URL» уже существует',

            'title.max' => 'Поле «Заголовок» не должно превышать 255 символов',
        ];
    }

    /**
     * Правила проверки запроса.
     *
     * @param Request $request
     * 
     * @return array
     */
    public function rules(Request $request): array
    {
        return [
            'meta.title' => 'max:255',
            'meta.description' => 'max:255',
            'meta.keywords' => 'max:255',
            'meta.og:title' => 'max:255',
            'meta.og:description' => 'max:255',

            'og_image.crop.default' => [
                'nullable', 'json',
                new CropSize(968,475,'min', ''),
            ],

            'name' => 'required|max:255',
            'slug' => 'required|alpha_dash|max:255|unique:tags,slug,'.$request->get('tag_id'),
            'title' => 'max:255',
        ];
    }
}
