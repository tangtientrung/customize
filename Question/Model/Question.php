<?php
 
namespace AHT\Question\Model;

class Question extends \Magento\Framework\Model\AbstractModel implements \AHT\Question\Api\Data\QuestionInterface
{
    /**
     * Test's Statuses
     */
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;

    protected function _construct()
    {
        $this->_init('AHT\Question\Model\ResourceModel\Question');
    }

    /**
     * Prepare banner's statuses.
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_PENDING => __('Pending'), self::STATUS_APPROVED => __('Approved')];
    }

    public function getId()
    {
        return $this->getData("id");
    }

    public function getContent()
    {
        return $this->getData("content");
    }

    public function getUserId()
    {
        return $this->getData("user_id");
    }

    public function getProductId()
    {
        return $this->getData("product_id");
    }

    public function getStatus()
    {
        return $this->getData("status");
    }

    public function getType()
    {
        return $this->getData("type");
    }

    public function getQuestionId()
    {
        return $this->getData("question_id");
    }
    
    public function getCreatedAt()
    {
        return $this->getData("created_at");
    }

    public function getUpdatedAt()
    {
        return $this->getData("updated_at");
    }

    public function setId($id)
    {
        return $this->setData("id", $id);
    }

    public function setContent($content)
    {
        return $this->setData("content", $content);
    }

    public function setUserId($user_id)
    {
        return $this->setData("user_id", $user_id);
    }

    public function setProductId($product_id)
    {
        return $this->setData("product_id", $product_id);
    }

    public function setStatus($status)
    {
        return $this->setData("status", $status);
    }

    public function setType($type)
    {
        return $this->setData("type", $type);
    }

    public function setQuestionId($question_id)
    {
        return $this->setData("question_id", $question_id);
    }

    public function setCreatedAt($created_at)
    {
        return $this->setData("created_at", $created_at);
    }

    public function setUpdatedAt($updated_at)
    {
        return $this->setData("updated_at", $updated_at);
    }

    
}