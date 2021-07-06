<?php

namespace AHT\Question\Api\Data;

interface QuestionInterface
{
	const ID = 'id';

	/**
     * Get question id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set question id
     *
     * @param int $id
     * @return @this
     */
    public function setId($id);

    /**
     * Get question content
     *
     * @return string|null
     */
    public function getContent();

    /**
     * Set question content
     *
     * @param string $content
     * @return null
     */
    public function setContent($content);

    /**
     * Get question user_id
     *
     * @return int|null
     */
    public function getUserId();

    /**
     * Set question user_id
     *
     * @param int $user_id
     * @return null
     */
    public function setUserId($user_id);

    /**
     * Get question product_id
     *
     * @return int|null
     */
    public function getProductId();

    /**
     * Set question product_id
     *
     * @param int $product_id
     * @return null
     */
    public function setProductId($product_id);

    /**
     * Get question question_id
     *
     * @return int|null
     */
    public function getQuestionId();

    /**
     * Set question question_id
     *
     * @param int $question_id
     * @return null
     */
    public function setQuestionId($question_id);

    /**
     * Get question status
     *
     * @return int|null
     */
    public function getStatus();

    /**
     * Set question status
     *
     * @param int $status
     * @return null
     */
    public function setStatus($status);

    /**
     * Get question type
     *
     * @return int|null
     */
    public function getType();

    /**
     * Set question type
     *
     * @param int $type
     * @return null
     */
    public function setType($type);

    /**
     * Get question created_at
     *
     * @return timestamp|null
     */
    public function getCreatedAt();

    /**
     * Set question created_at
     *
     * @param timestamp $created_at
     * @return null
     */
    public function setCreatedAt($created_at);

    /**
     * Get question updated_at
     *
     * @return timestamp|null
     */
    public function getUpdatedAt();

    /**
     * Set question updated_at
     *
     * @param timestamp $updated_at
     * @return null
     */
    public function setUpdatedAt($updated_at);
   
}