<?php

namespace Eyaylagul\Talk\Conversations;

use SebastianBerc\Repositories\Repository;

class ConversationRepository extends Repository
{
    /*
     * this method is default method for repository package
     *
     * @return  \Nahid\Talk\Conersations\Conversation
     * */
    public function takeModel()
    {
        return Conversation::class;
    }

    /*
     * check this given user is exists
     *
     * @param   int $id
     * @return  bool
     * */
    public function existsById($id)
    {
        $conversation = $this->find($id);
        if ($conversation) {
            return true;
        }

        return false;
    }

    /*
     * check this given two users is already make a conversation
     *
     * @param   int $user1
     * @param   int $user2
     * @return  int|bool
     * */
    public function isExistsAmongTwoUsers($user1, $user2, $threadId)
    {
        $conversation = Conversation::where('user_one', $user1)
            ->where('user_two', $user2)->where('thread_id', $threadId);

        if ($conversation->exists()) {
            return $conversation->first()->id;
        }

        return false;
    }

    /*
     * check this given user is involved with this given $conversation
     *
     * @param   int $conversationId
     * @param   int $userId
     * @return  bool
     * */
    public function isUserExists($conversationId, $userId)
    {
        $exists = Conversation::where('id', $conversationId)
            ->where(function ($query) use ($userId) {
                $query->where('user_one', $userId)->orWhere('user_two', $userId);
            })
            ->exists();

        return $exists;
    }

    /*
     * check this given user is involved with this given $thread
     *
     * @param   int $threadId
     * @param   int $userId
     * @return  bool
     * */
    public function isUserExistsInThread($threadId, $userId)
    {
        $exists = Conversation::where('thread_id', $threadId)
            ->where(function ($query) use ($userId) {
                $query->where('user_one', $userId)->orWhere('user_two', $userId);
            })
            ->exists();

        return $exists;
    }

    /*
     * retrieve all message thread without soft deleted message with latest one message and
     * sender and receiver user model
     *
     * @param   int $threadId
     * @param   int $offset
     * @param   int $take
     * @return  collection
     * */
    public function getThread($userId, $threadId, $offset = 0, $take = 15)
    {
        $model = config('talk.user.thread.model');

        $thread = $model::find($threadId)
            ->conversations()
            ->with(['messages' => function ($q) use ($offset, $take) {
                    return $q->offset($offset)
                        ->take($take)
                        ->get();

                }, 'userone', 'usertwo', 'thread'])
            ->first();


        return $thread;
    }

    /*
     * retrieve all message thread without soft deleted message with latest one message and
     * sender and receiver user model
     *
     * @param   int $user
     * @param   int $offset
     * @param   int $take
     * @return  collection
     * */
    public function getAllThreads($userId, $order = 'desc', $offset = 0, $take = 15)
    {
        
        $msgThreads = Conversation::with(['messages' => function ($q) use ($userId) {
            return $q->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                    ->where('deleted_from_sender', 0);
            })
                ->orWhere(function ($q) use ($userId) {
                    $q->where('user_id', '!=', $userId);
                    $q->where('deleted_from_receiver', 0);
                })
                ->latest();
        }, 'userone', 'usertwo', 'thread'])
            ->where('user_one', $userId)
            ->orWhere('user_two', $userId)
            ->offset($offset)
            ->take($take)
            ->orderBy('updated_at', $order)
            ->get();


        $threads = [];

        foreach ($msgThreads as $msgThread) {
            $collection = (object) null;
            $collection->thread = $msgThread->thread;
            $conversationWith = ($msgThread->userone->id == $userId) ? $msgThread->usertwo : $msgThread->userone;
            $collection->message = $msgThread->messages->first();
            $collection->withUser = $conversationWith;
            $threads[] = $collection;
        }


        return collect($threads);
    }

    

    /*
     * get all conversations by given conversation id
     *
     * @param   int $conversationId
     * @param   int $userId
     * @param   int $offset
     * @param   int $take
     * @return  collection
     * */
    public function getMessagesById($conversationId, $userId, $offset = 0, $take = 15)
    {
        return Conversation::with(['messages' => function ($query) use ($userId, $offset, $take) {
            $query->where(function ($qr) use ($userId) {
                $qr->where('user_id', '=', $userId)
                    ->where('deleted_from_sender', 0);
            })
            ->orWhere(function ($q) use ($userId) {
                $q->where('user_id', '!=', $userId)
                    ->where('deleted_from_receiver', 0);
            });

            $query->offset($offset)->take($take);

        }])->with(['userone', 'usertwo'])->find($conversationId);

    }

    /*
     * get all conversations with soft deleted message by given conversation id
     *
     * @param   int $conversationId
     * @param   int $offset
     * @param   int $take
     * @return  collection
     * */
    public function getMessagesAllById($conversationId, $offset = 0, $take = 15)
    {
        return $this->with(['messages' => function ($q) use ($offset, $take) {
            return $q->offset($offset)
                ->take($take);
        }, 'userone', 'usertwo'])->find($conversationId);
    }
}
