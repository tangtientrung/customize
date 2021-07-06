<?php
namespace AHT\Question\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface QuestionRepositoryInterface
{
	 /**
     * Save Post.
     *
     * @param \AHT\Question\Api\Data\QuestionInterface $post
     * 
     * @return \AHT\Question\Api\Data\QuestionInterface
     */
    public function save(\AHT\Question\Api\Data\QuestionInterface $post);

    /**
     * Get object by id
     *
     * @return \AHT\Question\Api\Data\QuestionInterface
     */
    public function getById(String $id);

    /**
     * Get All
     * 
     * @return \AHT\Question\Api\Data\QuestionInterface
     */
    public function getList();
    
    /**
     * Create post.
     *
     * @param \AHT\Question\Api\Data\QuestionInterface $post
     * 
     * @return \AHT\Question\Api\Data\QuestionInterface
     */
    public function createPost(\AHT\Question\Api\Data\QuestionInterface $post);

    /**
     * Update post
     *
     * @param String $id
     * @param \AHT\Question\Api\Data\PostInterface $post
     * 
     * @return null
     */
    public function updatePost(String $id, \AHT\Question\Api\Data\QuestionInterface $post);

    /**
     * Delete Post by ID.
     *
     * @param string $postId
     * @return \AHT\Question\Api\Data\QuestionInterface
     */
    public function deleteById($postId);
}