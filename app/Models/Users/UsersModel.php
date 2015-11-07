<?php 

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

use Jenssegers\Mongodb\Model as Eloquent;

class UsersModel extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array(
        'uid' => 'string',
        'username' => 'string',
        'email' => 'string',
        'password' => 'string',
        'picture' => 'string',
        'token' => 'string',
    );

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'created_device_type',
        'event_list',
        'fb_extended_token',
        'fb_token',
        'fb_token_expiry',
        'fb_returned_data',
        'password',
        'performance_appraisal',
        'profiling',
        'reverse_geo',
        'reset_code',
        'reset_expired_at',
        'insta_token',
        'insta_returned_data',
    );

    public static $rules = array(
        'username' => 'min:6|max:30|alpha_dash|required',
        'email' => 'email|max:100|required',
        'password' => 'min:8|max:50|required',
        'name' => 'min:1|max:50|required',
        'dob' => 'date|date_format:Y-m-d',
        'fb_id' => 'numeric',
        'description' => 'max:500',
        'personal_link' => 'max:500',
        'insta_id' => 'numeric',
    );

    public static $rules_message = array(
        'username' => array(
            'min' => 'seeties.auth.username.min',
            'max' => 'seeties.auth.username.max',
            'alpha_dash' => 'seeties.auth.username.alpha_dash',
            'required' => 'seeties.auth.username.required',
        ),
        'email' => array(
            'email' => 'seeties.auth.email.email',
            'max' => 'seeties.auth.email.max',
            'required' => 'seeties.auth.email.required',
        ),
        'password' => array(
            'min' => 'seeties.auth.password.min',
            'max' => 'seeties.auth.password.max',
            'required' => 'seeties.auth.password.required',
        ),
        'name' => array(
            'min' => 'seeties.auth.name.min',
            'max' => 'seeties.auth.name.max',
            'required' => 'seeties.auth.name.required',
        ),
        'dob' => array(
            'date' => 'seeties.auth.dob.date.invalid',
            'date_format' => 'seeties.auth.dob.format.invalid',
        ),
        'fb_id' => array(
            'numeric' => 'seeties.auth.facebookid.numeric',
        ),
        'description' => array(
            'max' => 'seeties.auth.description.max',
        ),
        'personal_link' => array(
            'max' => 'seeties.auth.link.max',
        ),
        'insta_id' => array(
            'numeric' => 'seeties.auth.instagramid.numeric',
        ),
    );
}
