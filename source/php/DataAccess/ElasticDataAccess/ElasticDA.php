<?php
require_once 'Connection.php';
require_once 'FileHelper.php';

final class ElasticDA
{
//MARK:- Properties
    private $client;
    private $helper;

//MARK:- Public constructors
    public function __construct()
    {
        $this->client = ElasticConnection::open_connection()
            ->get_client();
        $this->helper = new FileHelper();
    }

//MARK:- Index Functions

    //TO-DO: Indexing file's info at @var($file_input) into Elastic database
    public function indexing($file_input, $file_path)
    {
        $info = $file_input->getInfo();
        $content = $this->helper->getContent($info['type'], $file_path);
        $params = [
            'index' => 'file',
            'type' => 'content',
            'id' => $info['file_id'],
            'body' => [
                'user_id' => $info['user_id'],
                'name' => $info['name'],
                'size' => $info['size'],
                'date' => $info['date'],
                'descr' => $info['descr'],
                'type' => $info['type'],
                'status' => $info['status'],
                'content' => $content,
            ],
        ];
        try {
            $response = $this->client->index($params);
            return "Success";
        } catch (Elasticsearch\Common\Exceptions $e) {
            echo $e;
            return null;
        }
    }

    //TO-DO: Indexing file's shared at @var($file_input) into Elastic database
    public function indexing_shared($owner, $receiver, $file_id)
    {
        $params = [
            'index' => 'share',
            'type' => 'content',
            'id' => $file_id,
            'body' => [
                'owner_id' => $owner,
                'receiver_id' => $receiver,
                'content' => "shared_file",
            ],
        ];
        try {
            $response = $this->client->index($params);
            return "Success";
        } catch (Elasticsearch\Common\Exceptions $e) {
            echo $e;
            return null;
        }
    }

    //TO-DO: Indexing file's info at @var($folder_id) into Elastic database
    public function index_folder($folder_id, $status)
    {
        $params = [
            'index' => 'folder',
            'type' => 'info',
            'id' => $folder_id,
            'body' => [
                'status' => $status,
            ],
        ];
        try {
            $response = $this->client->index($params);
            return "Success";
        } catch (Elasticsearch\Common\Exceptions $e) {
            echo $e;
            return null;
        }
    }
//MARK:- Search Functions
    //TO-DO: Search with @var($file_name as String) in Elastic database
    public function search_with_name($file_name, $user_id)
    {
        $params = [
            'index' => 'file',
            'type' => 'content',
            'size' => 100,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            ['match' => ['status' => 1]],
                            ['match' => ['user_id' => $user_id]],
                            ['match' => ['name' => $file_name]],
                        ],
                    ],
                ],
            ],
        ];
        try {
            $response = $this->client->search($params);
            $resource = array();
            for ($i = 0; $i < count($response['hits']['hits']); $i++) {
                $file_id = strtoupper(json_encode($response['hits']['hits'][$i]['_id']));
                array_push($resource, $file_id);
            }
            ksort($resource);
            return $resource;
        } catch (Elasticsearch\Common\Exceptions $e) {
            echo $e;
            return null;
        }
    }

    //TO-DO: Search with @var($file_type as Integer) in Elastic database
    public function search_with_type($file_type, $user_id)
    {
        $params = [
            'index' => 'file',
            'type' => 'content',
            'size' => 100,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            ['match' => ['status' => 1]],
                            ['match' => ['user_id' => $user_id]],
                            ['match' => ['type' => $file_type]],
                        ],
                    ],
                ],
            ],
        ];
        try {
            $response = $this->client->search($params);
            $resource = array();
            for ($i = 0; $i < count($response['hits']['hits']); $i++) {
                $file_id = strtoupper(json_encode($response['hits']['hits'][$i]['_id']));
                array_push($resource, $file_id);
            }
            ksort($resource);
            return $resource;
        } catch (Elasticsearch\Common\Exceptions $e) {
            echo $e;
            return null;
        }
    }

    //TO-DO: Search with @var($file_size as Integer) in Elastic database
    public function search_with_size($file_size, $user_id)
    {
        $params = [
            'index' => 'file',
            'type' => 'content',
            'size' => 100,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            ['match' => ['status' => 1]],
                            ['match' => ['user_id' => $user_id]],
                            ['match' => ['size' => $file_size]],
                        ],
                    ],
                ],
            ],
        ];
        try {
            $response = $this->client->search($params);
            $resource = array();
            for ($i = 0; $i < count($response['hits']['hits']); $i++) {
                $file_id = strtoupper(json_encode($response['hits']['hits'][$i]['_id']));
                array_push($resource, $file_id);
            }
            ksort($resource);
            return $resource;
        } catch (Elasticsearch\Common\Exceptions $e) {
            echo $e;
            return null;
        }
    }

    //TO-DO: Search with @var($file_dateModified as Date) in Elastic database
    public function search_with_date($file_date, $user_id)
    {
        $params = [
            'index' => 'file',
            'type' => 'content',
            'size' => 100,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            ['match' => ['status' => 1]],
                            ['match' => ['user_id' => $user_id]],
                            ['match' => ['date' => $file_date]],
                        ],
                    ],
                ],
            ],
        ];
        try {
            $response = $this->client->search($params);
            $resource = array();
            for ($i = 0; $i < count($response['hits']['hits']); $i++) {
                $file_id = strtoupper(json_encode($response['hits']['hits'][$i]['_id']));
                array_push($resource, $file_id);
            }
            ksort($resource);
            return $resource;
        } catch (Elasticsearch\Common\Exceptions $e) {
            echo $e;
            return null;
        }
    }

    //TO-DO: Search with @var($file_content as String) in Elastic database
    public function search_with_content($file_content, $user_id)
    {
        $params = [
            'index' => 'file',
            'type' => 'content',
            'size' => 100,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            ['match' => ['status' => 1]],
                            ['match' => ['user_id' => $user_id]],
                            ['match' => ['content' => $file_content]]
                        ],
                    ],
                ],
            ],
        ];
        try {
            $response = $this->client->search($params);
            $resource = array();
            for ($i = 0; $i < count($response['hits']['hits']); $i++) {
                $file_id = strtoupper(json_encode($response['hits']['hits'][$i]['_id']));
                array_push($resource, $file_id);
            }
            ksort($resource);
            return $resource;
        } catch (Elasticsearch\Common\Exceptions $e) {
            echo $e;
            return null;
        }
    }
    //TO-DO: Search file with @var($status as Integer) in Elastic database
    public function search_with_status($status, $index, $user_id)
    {
        if ($index == "file") {
            $params = [
                'index' => $index,
                'type' => 'content',
                'size' => 100,
                'body' => [
                    'query' => [
                        'bool' => [
                            'must' => [
                                ['match' => ['status' => $status]],
                                ['match' => ['user_id' => $user_id]],
                            ],
                        ],
                    ],
                ],
            ];
            try {
                $response = $this->client->search($params);
                $resource = array();
                for ($i = 0; $i < count($response['hits']['hits']); $i++) {
                    $file_id = strtoupper(json_encode($response['hits']['hits'][$i]['_id']));
                    array_push($resource, $file_id);
                }
                return $resource;
            } catch (Elasticsearch\Common\Exceptions $e) {
                echo $e;
                return null;
            }
        } else if ($index == "share") {
            $params = [
                'index' => $index,
                'type' => 'content',
                'size' => 100,
                'body' => [
                    'query' => [
                        'bool' => [
                            'must' => [
                                ['match' => ['receiver_id' => $user_id]],
                            ],
                        ],
                    ],
                ],
            ];
            try {
                $response = $this->client->search($params);
                $resource = array();
                for ($i = 0; $i < count($response['hits']['hits']); $i++) {
                    $file_id = strtoupper(json_encode($response['hits']['hits'][$i]['_id']));
                    array_push($resource, $file_id);
                }
                return $resource;
            } catch (Elasticsearch\Common\Exceptions $e) {
                echo $e;
                return null;
            }
        }
    }
    //TO-DO: Search folder with @var($status as Integer) in Elastic database
    public function search_folder_with_status($status, $user_id)
    {
        $params = [
            'index' => 'folder',
            'type' => 'info',
            'size' => 100,
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            'match' => ['status' => $status],
                        ],
                    ],
                    'must' => [
                        ['match' => ['status' => 1]],
                        ['match' => ['user_id' => $user_id]],
                    ],
                ],
            ],
        ];

        try {
            $response = $this->client->search($params);
            $resource = array();
            for ($i = 0; $i < count($response['hits']['hits']); $i++) {
                $file_id = strtoupper(json_encode($response['hits']['hits'][$i]['_id']));
                array_push($resource, $file_id);
            }
            return $resource;
        } catch (Elasticsearch\Common\Exceptions $e) {
            echo $e;
            return null;
        }
    }
//MARK:- Update Function

    //TO-DO: Update file with $id and $name
    public function update_with_name($id, $name)
    {
        $params = [
            'index' => 'file',
            'type' => 'content',
            'id' => $id,
            'body' => [
                'doc' => [
                    'name' => $name,
                ],
            ],
        ];
        try {
            $response = $this->client->update($params);
            return $response;
        } catch (Elasticsearch\Common\Exceptions $e) {
            echo $e;
            return null;
        }
    }

    //TO-DO: Update file with $id and $status
    public function update_with_status($id, $status)
    {
        $params = [
            'index' => 'file',
            'type' => 'content',
            'id' => $id,
            'body' => [
                'doc' => [
                    'status' => $status,
                ],
            ],
        ];
        try {
            $response = $this->client->update($params);
            return $response;
        } catch (Elasticsearch\Common\Exceptions $e) {
            echo $e;
            return null;
        }
    }

    //TO-DO: Update folder with $id and $status
    public function update_folder_with_name($id, $name)
    {
        $params = [
            'index' => 'folder',
            'type' => 'info',
            'id' => $id,
            'body' => [
                'doc' => [
                    'name' => $name,
                ],
            ],
        ];
        try {
            $response = $this->client->update($params);
            return $response;
        } catch (Elasticsearch\Common\Exceptions $e) {
            echo $e;
            return null;
        }
    }

    //TO-DO: Update folder with $id and $status
    public function update_folder_with_status($id, $status)
    {
        $params = [
            'index' => 'folder',
            'type' => 'info',
            'id' => $id,
            'body' => [
                'doc' => [
                    'status' => $status,
                ],
            ],
        ];
        try {
            $response = $this->client->update($params);
            return $response;
        } catch (Elasticsearch\Common\Exceptions $e) {
            echo $e;
            return null;
        }
    }
}
