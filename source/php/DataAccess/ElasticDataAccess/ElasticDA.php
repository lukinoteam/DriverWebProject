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

//MARK:- Functions

    //TO-DO: Indexing file's info at @var($file_input) into Elastic database 
    public function indexing($file_input, $file_path) {
        $info = $file_input->getInfo();
        $content = $this->helper->getContent($info['type'], $file_path);
        $params = [
            'index' => 'file',
            'type' => 'content',
            'id' => "id" . $info['name'],
            'body' => [
                'name' => $info['name'],
                'size' => $info['size'],
                'date' => $info['date'],
                'descr' => $info['descr'],
                'type' => $info['type'],
                'content' => $content
            ]
        ];
        try {
            $response = $this->client->index($params);
            return "Success";
        } catch (Elasticsearch\Common\Exceptions $e) {
            echo $e;
            return null;
        }
    }

    //TO-DO: Search with @var($file_name as String) in Elastic database 
    

    //TO-DO: Search with @var($file_type as Integer) in Elastic database 

    //TO-DO: Search with @var($file_size as Integer) in Elastic database 

    //TO-DO: Search with @var($file_dateModified as Date) in Elastic database 

    //TO-DO: Search with @var($file_description as String) in Elastic database  
    
    
//MARK:- Private function to support main features
    


}
