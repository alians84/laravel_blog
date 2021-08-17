<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $unique = 'unique:posts,slug';
        if (in_array($this->route()->getName(), ['admin.post.update', 'user.post.update'])) {
            // получаем модель Post через маршрут admin/post/{post}
            $model = $this->route('post');
            $unique = 'unique:posts,slug,'.$model->id.',id';
        }
        return [
            'name'=>[
                'required',
                'string',
                'min:3',
                'max:100',
            ],
            'slug' => [
                'required',
                'max:100',
                $unique,
                'regex:~^[-_a-z0-9]+$~i',
            ],
            'category_id'=>[
                'required',
                'numeric',
                'min:1'
            ],
            'content'=>[
                'required',
                'min:500',
            ],
            'image'=>[
                'mimes:jpeg,jpg,png',
                'max:5000'
            ],
        ];
    }

    /**
    * Возвращает массив собщений об ошибках для заданных правил
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required'=>'Поле «:attribute» обязательно для заполнения',
            'unique'=>'Такое занчение поля «:attribute» уже использутеся',
            'min'  =>[
                'string'=>'Поле «:attribute» должно быть не меньше :min символов',
                'numeric'=>'Нужно выбрать категорию нового поста блога',
                'file'=>'Файл «:attribute» должен быть не меншьне :min Кбайт'
            ],
            'max'=>[
                'string'=>'Поле «:attribute» должно быть не больше :max символов',
                'file'=>'Файл «:attribute» должен быть не больше :max Кбайт'
            ],
            'mimes'=>'Файл «:attribute» должен иметь формат :values',
        ];
    }
    /**
     * Возвращает массив дружественных пользователю названий полей
     *
     * @return array
     */
public function attributes()
{
return[
    'name'=>'Наименование',
    'slug'=>'ЧПУ (англ.)',
    'category_id'=>'Категория',
    'excerpt'=>'Анонс поста',
    'content'=>'Текст поста',
    'image'=>'Изображение',
] ;
}

}
