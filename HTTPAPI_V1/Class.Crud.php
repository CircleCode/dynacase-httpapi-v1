<?php
/*
 * @author Anakeen
 * @license http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License
 * @package FDL
*/

namespace Dcp\HttpApi\V1;

abstract class Crud
{
    const CREATE = "CREATE";
    const READ = "READ";
    const UPDATE = "UPDATE";
    const DELETE = "DELETE";
    /**
     * Regexp that check if the current path can be processed by the current CRUD
     *
     * @var string
     */
    /**
     * @var RecordReturnMessage[]
     */
    protected $messages = array();
    protected $path = null;
    /**
     * Request parameters extracted from the URI
     *
     * @var array
     */
    protected $urlParameters = array();

    /**
     * Request parameters extracted from the content of the request
     *
     * @var array
     */
    protected $contentParameters = array();

    public function __construct()
    {

    }
    //region CRUD part
    /**
     * Create new ressource
     * @return mixed
     */
    abstract public function create();

    /**
     * Read a ressource
     * @param string|int $resourceId Resource identifier
     * @return mixed
     */
    abstract public function read($resourceId);

    /**
     * Update the ressource
     * @param string|int $resourceId Resource identifier
     * @return mixed
     */
    abstract public function update($resourceId);

    /**
     * Delete ressource
     * @param string|int $resourceId Resource identifier
     * @return mixed
     */
    abstract public function delete($resourceId);
    //endregion
    /**
     * Execute the request
     * Find the CRUD action to execute and execute it
     *
     * @param array $messages list of messages to send
     * @return mixed data of process
     * @throws Exception
     */
    public function execute($method, array & $messages = array())
    {

        switch ($method) {

            case "CREATE":
                $data = $this->create();
                break;

            case "READ":
                $data = $this->read($this->urlParameters["identifier"]);
                break;

            case "UPDATE":
                $data = $this->update($this->urlParameters["identifier"]);
                break;

            case "DELETE":
                $data = $this->delete($this->urlParameters["identifier"]);
                break;

            default:
                throw new Exception("API0102", $method);
        }
        $messages = $this->getMessages();
        return $data;
    }

    /**
     * Add a message to be sended with the response
     *
     * @param \Dcp\HttpApi\V1\RecordReturnMessage $message
     */
    public function addMessage(RecordReturnMessage $message)
    {
        $this->messages[] = $message;
    }

    /**
     * Get all the added messages
     *
     * @return \Dcp\HttpApi\V1\RecordReturnMessage[]
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set url context parameters
     *
     * @param array $parameters
     */
    public function setUrlParameters(Array $parameters) {
        $this->urlParameters = $parameters;
    }

    public function setContentParameters(Array $parameters) {
        $this->contentParameters = $parameters;
    }

    public function getEtagInfo() {
        return null;
    }


}
