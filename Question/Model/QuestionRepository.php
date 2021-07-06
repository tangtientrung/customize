<?php
namespace AHT\Question\Model;

use AHT\Question\Api\Data;
use AHT\Question\Api\QuestionRepositoryInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use AHT\Question\Model\ResourceModel\Question as ResourcePost;
use AHT\Question\Model\ResourceModel\Question\CollectionFactory as PostCollectionFactory;
use AHT\Question\Api\Data\QuestionInterface;

/**
 * Class PostRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class QuestionRepository implements QuestionRepositoryInterface
{
    /**
     * @var _resource
     */
    protected $_resource;

    /**
     * @var _postFactory
     */
    protected $_postFactory;

    /**
     * @var _postCollectionFactory
     */
    protected $_postCollectionFactory;

    /**
     * @var Data\PostSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;
    /**
     * @param ResourcePost $resource
     * @param PostFactory $PostFactory
     * @param Data\QuestionInterfaceFactory $dataPostFactory
     * @param PostCollectionFactory $PostCollectionFactory
     * @param Data\PostSearchResultsInterfaceFactory $searchResultsFactory
     */
    private $collectionProcessor;

    public function __construct(
        ResourcePost $resource,
        QuestionFactory $postFactory,
        Data\QuestionInterfaceFactory $dataPostFactory,
        PostCollectionFactory $postCollectionFactory
    ) {
        $this->_resource = $resource;
        $this->_postFactory = $postFactory;
        $this->_postCollectionFactory = $postCollectionFactory;
    }

    /**
     * Save Post data
     *
     * @param  \AHT\Question\Api\Data\QuestionInterface $post
     * @return \AHT\Question\Api\Data\QuestionInterface
     */
    public function save(QuestionInterface $post)
    {
        try {
            $this->_resource->save($post);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the Post: %1', $exception->getMessage()),
                $exception
            );
        }

        return $post;
    }

    /**
     * Load Post data by given Post Identity
     *
     * @param [type] $id
     * @return \AHT\Question\Model\ResourceModel\Question
     */
    public function getById($id)
    {
        $postId = intval($id);
        $post = $this->_postFactory->create();
        $post->load($postId);
        if (!$post->getId()) {
            throw new NoSuchEntityException(__('The CMS Post with the "%1" ID doesn\'t exist.', $postId));
        }
        $result = $post;
        return $result;
    }

    /**
     * function get all data
     *
     * @return \AHT\Question\Api\Data\QuestionInterface
     */
    public function getList()
    {
        $collection = $this->_postCollectionFactory->create();
        return $collection->getData();
    }

    /**
     * Create post.
     *  
     * @return \AHT\Question\Api\Data\QuestionInterface
     * 
     * @throws LocalizedException
     */
    public function createPost(QuestionInterface $post)
    {
        try {
            $this->_resource->save($post);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the Post: %1', $exception->getMessage()),
                $exception
            );
        }
        return json_encode(array(
            "status" => 200,
            "message" => $post->getData()
        ));
        
    }


    public function updatePost(String $id, QuestionInterface $post)
    {

        try {
            $objPost = $this->_postFactory->create();
            $id = intval($id);
            $objPost->setId($id);
            //Set full collum
            $objPost->setData($post->getData());
            $this->_resource->save($objPost);

            return $objPost->getData();
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the Post: %1', $exception->getMessage()),
                $exception
            );
        }
    }

    public function deleteById($postId)
    {
        $id = intval($postId);
        if($this->_resource->delete($this->getById($id))) {
            return json_encode([
                "status" => 200,
                "message" => "Successfully"
            ]);
        }
    }
}