<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }

    public function followings()
    {
                                             // どのテーブルから 何を基準に  何を取得する
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }

    public function follow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身ではないかの確認
        $its_me = $this->id == $userId;

        if ($exist || $its_me) {
            // 既にフォローしていれば何もしない
            return false;
        } else {
            // 未フォローであればフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }

    public function unfollow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {
            // 既にフォローしていればフォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            // 未フォローであれば何もしない
            return false;
        }
    }

    public function is_following($userId)
    {
        return $this->followings()->where('follow_id', $userId)->exists();
    }

    public function feed_microposts()
    {
        $follow_user_ids = $this->followings()->pluck('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        return Micropost::whereIn('user_id', $follow_user_ids);
    }

    public function favorite()
    {
                                             // どのテーブルから 何を基準に  何を取得する
        return $this->belongsToMany(Micropost::class, 'favorite', 'user_id', 'microposts_id')->withTimestamps();
    }

    public function add_favorite($microposts_id)
    {
        // 既にお気に入りしているかの確認
        $exist = $this->is_favorite($microposts_id);

        if ($exist) {
            // 既にお気に入りしていれば何もしない
            return false;
        } else {
            // まだお気に入りしていなければお気に入りする
            $this->favorite()->attach($microposts_id);
            return true;
        }
    }

    public function unfavorite($microposts_id)
    {
        // 既にお気に入りしているかの確認
        $exist = $this->is_favorite($microposts_id);

        if ($exist) {
            // 既にお気に入りしていればお気に入りを外す
            $this->favorite()->detach($microposts_id);
            return true;
        } else {
            // お気に入りでなければ何もしない
            return false;
        }
    }

    public function is_favorite($microposts_id)
    {
        return $this->favorite()->where('microposts_id', $microposts_id)->exists();
    }
}