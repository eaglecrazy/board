<?php



namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    //авторизацию нужно проверять при этом запросе
    public function authorize()
    {
        return true;
    }

    //возвращает массив правил валидации
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:3', 'confirmed'],
        ];
    }
}


/**
 * @SWG\Definition(
 *     definition="RegisterRequest",
 *     type="object",
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="email", type="string"),
 *     @SWG\Property(property="password", type="string"),
 *     @SWG\Property(property="password_confirmation", type="string"),
 * )
 */
