<?php


class Pivot_ItinerairesController
{

    public function __construct(private Pivot_ItinerairesGateway $gateway)
    {
    }
        
    
    public function processRequest(string $method, ?string $id): void
    {
    
        echo json_encode("Pivot_Itineraires_Controller");
        exit;
        

        if ($id) {

            $this->processResourceRequest($method, $id);

        } else {

            $this->processCollectionpRequest($method);

            }

    }



    private function processResourceRequest(string $method, string $id): void {

        $product = $this->gateway->get($id);

        if ($product == false) {
            http_response_code(404);
            echo json_encode(["message" => "Product not found."]);
            return;
        }

        switch ($method) {
            case "GET":
                http_response_code(200);
                echo json_encode($product);
                break;
            
            case "PATCH":

                $data = (array) json_decode(file_get_contents("php://input"), true);

                $errors = $this->getValidationErrors($data, false);

                if (! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    
                    break;
                }

                $rows = $this->gateway->update($product, $data);

                http_response_code(200);
                echo(json_encode([
                    "message" => "Product(s) $id updated",
                    "rows updates" => $rows
                ]));

                break;

            case "DELETE":

                $rows = $this->gateway->delete($id);
                echo(json_encode([
                    "message" => "Product(s) $id deleted",
                    "rows deleted" => $rows
                ]));

                break;

            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE");



            }

    }






    private function processCollectionpRequest(string $method): void {


        switch ($method) {
            case "GET":
                echo json_encode($this->gateway->getAll());
                break;

            case "POST":


                $data = (array) json_decode(file_get_contents("php://input"), true);

                $errors = $this->getValidationErrors($data, true);

                if (! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    
                    break;
                }

                $id = $this->gateway->create($data);

                http_response_code(201);
                echo(json_encode([
                    "message" => "Product created",
                    "id" => $id
                ]));
                break;

            default:
                http_response_code(405);
                header("Allow: GET, POST");



        
        }

    }






    private function getValidationErrors(array $data, bool $is_new): array {

        $errors = [];

        if ( ! array_key_exists("name", $data) && $is_new) {
            $errors[] = "Name is mandatory for a new record";
        }

        if ($is_new && empty($data)) {
            $errors[] = "Name is required";
        }

        if (array_key_exists("size", $data)) {

            if (filter_var($data["size"], FILTER_VALIDATE_INT) === false) {
                $errors[] = "size must be an integer";
            }
        }




        return $errors;
    }






}
