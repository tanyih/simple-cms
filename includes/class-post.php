<?php

// static class
class Post
{
    /**
     * Retrieve all posts from database
     */
    public static function getAllPosts()
    {
        return DB::connect()->select(
            'SELECT * FROM posts ORDER BY id DESC',
            [],
            true
        );
    }

    /**
     * Retrieve post data by id
     */
    public static function getPostByID( $post_id )
    {
        return DB::connect()->select(
            'SELECT * FROM posts WHERE id = :id',
            [
                'id' => $post_id
            ]
        );
    }

    /**
     * Add new post
     */
    public static function add( $status, $title, $content )
    {
        return DB::connect()->insert(
            'INSERT INTO posts (status, title, content) 
            VALUES (:status, :title, :content)',
            [
                'status' => $status,
                'title' => $title,
                'content' => $content
            ]
        );
    }

    /**
     * Update post details
     */
    public static function update( $id, $status, $title, $content = null )
    {

        // setup params
        $params = [
            'id' => $id,
            'status' => $status,
            'title' => $title,
            'content' => $content
        ];


        // update post data into the database
        return DB::connect()->update(
            'UPDATE posts SET title = :title, content = :content,' .' status = :status WHERE id = :id',
            $params
        );
    }

    /**
     * Delete post
     */
    public static function delete( $post_id )
    {
        return DB::connect()->delete(
            'DELETE FROM posts where id =:id', 
            [
                'id' => $post_id
            ]
        );
    }
}
?>