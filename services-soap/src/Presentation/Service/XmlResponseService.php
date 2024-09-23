<?php

namespace App\Presentation\Service;

class XmlResponseService
{
    /**
     * Genera una respuesta SOAP válida con datos exitosos.
     *
     * @param array $data Datos a incluir en la respuesta.
     * @param string $rootElement Nombre del elemento raíz de la respuesta.
     * @return string XML en formato SOAP.
     */
    public function toSoapResponse(array $data, string $rootElement = 'Response'): string
    {
        // Crear el sobre SOAP (Envelope) con su espacio de nombres
        $xml = new \SimpleXMLElement('<Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope"/>');
        // Añadir el cuerpo (Body) dentro del sobre SOAP
        $body = $xml->addChild('Body');
        // Añadir el elemento raíz de la respuesta dentro del cuerpo
        $response = $body->addChild($rootElement);

        // Añadir los datos al XML, manejando arrays y objetos
        $this->addDataToXml($response, $data);

        // Retornar el XML en formato SOAP
        return $xml->asXML();
    }

    /**
     * Genera una respuesta SOAP válida con un error (Fault).
     *
     * @param string $code Código del error.
     * @param string $message Descripción del error.
     * @return string XML en formato SOAP con Fault.
     */
    public function toSoapFault(string $code, string $message): string
    {
        // Crear el sobre SOAP (Envelope) con su espacio de nombres
        $xml = new \SimpleXMLElement('<Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope"/>');
        // Añadir el cuerpo (Body) dentro del sobre SOAP
        $body = $xml->addChild('Body');
        // Añadir el elemento Fault dentro del cuerpo en caso de error
        $fault = $body->addChild('Error');
        $fault->addChild('cod_error', $code);
        $fault->addChild('message_error', htmlspecialchars($message));
        $fault->addChild('success', false);

        // Retornar el XML en formato SOAP con el Fault
        return $xml->asXML();
    }

    /**
     * Función auxiliar que añade datos al XML, manejando arrays y objetos.
     *
     * @param \SimpleXMLElement $xmlElement El elemento XML en el que se insertarán los datos.
     * @param array|mixed $data Datos que se añadirán al XML.
     */
    private function addDataToXml(\SimpleXMLElement $xmlElement, $data): void
    {
        foreach ($data as $key => $value) {
            // Si el valor es un objeto, convertirlo a un array
            if (is_object($value)) {
                $value = $this->objectToArray($value);
            }

            // Si el valor es un array, procesarlo recursivamente
            if (is_array($value)) {
                $childElement = $xmlElement->addChild($key);
                $this->addDataToXml($childElement, $value);
            } else {
                // Añadir el valor como child, escapando los caracteres especiales
                $xmlElement->addChild($key, htmlspecialchars($value));
            }
        }
    }

    /**
     * Convierte un objeto a array.
     *
     * @param object $object Objeto que se convertirá a array.
     * @return array El objeto convertido a array.
     */
    private function objectToArray($object): array
    {
        // Si el objeto tiene un método toArray, úsalo
        if (method_exists($object, 'toArray')) {
            return $object->toArray();
        }

        // Convertir el objeto a array usando json_encode y json_decode
        return json_decode(json_encode($object), true);
    }
}
