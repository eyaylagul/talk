<?php

namespace Eyaylagul\Talk\Conversations;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $table = 'conversations';
    public $timestamps = true;
    public $fillable = [
        'user_one',
        'user_two',
        'thread_id',
        'status',
    ];

    /*
     * make a relation between message
     *
     * return collection
     * */
    public function messages()
    {
        return $this->hasMany('Eyaylagul\Talk\Messages\Message', 'conversation_id')
            ->with('sender');
    }

    /*
     * make a relation between first user from conversation
     *
     * return collection
     * */
    public function userone()
    {
        return $this->belongsTo(config('talk.user.model', 'App\User'),  'user_one');
    }

    /*
   * make a relation between second user from conversation
   *
   * return collection
   * */
    public function usertwo()
    {
        return $this->belongsTo(config('talk.user.model', 'App\User'),  'user_two');
    }

    /*
   * make a relation between Thread from conversation
   *
   * return collection
   * */
    public function thread()
    {
    	return $this->belongsTo(config('talk.user.thread.model', 'App\Models\UserTreatment'), 'thread_id');
    }
}
