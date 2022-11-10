<?php
/**
 * SDK for AFIP Electronic Billing (wsfe1)
 * 
 * @link http://www.afip.gob.ar/fe/documentos/manual_desarrollador_COMPG_v2_10.pdf WS Specification
 *
 * @author 	Afip SDK
 * @package Afip
 * @version 0.7
 **/

class CartaDePorte extends AfipWebService {

	var $soap_version 	= SOAP_1_1;
	var $WSDL 			= '_cartaporte_production.wsdl';
	var $URL 			= 'https://serviciosjava.afip.gob.ar/wscpe/services/soap';
	var $WSDL_TEST 		= '_cartaporte.wsdl';
	var $URL_TEST 		= 'https://fwshomo.afip.gov.ar/wscpe/services/soap'; 

	/**
	 * Verificación del servicio
	 *
	 * Descripción: El método dummy verifica el estado y la disponibilidad de los provincias
	 * elementos principales del servicio (aplicación, autenticación y base de datos).
	 * 
	 * 2.7.1
	**/
	public function getServerStatus()
	{
		return $this->ExecuteRequest('dummy');
	}

  /**
	 * Consulta de provincias
	 *
	 * Descripción: Retorna un listado con el código y descripción de todas las provincias.
	 *
	 * 2.7.2
	**/
	public function getProvincias()
	{
		return $this->ExecuteRequest('consultarProvincias')->respuesta->provincia;
	}

  /**
	 * Consulta de localidades por provincias
	 *
	 * Descripción: Retorna un listado con el código y descripción de todas las localidades pertenecientes a la provincia indicada como parámetro
	 *
	 * 2.7.2
	**/
	public function getLocalidadesPorProvincia($codProvincia)
	{
		$req = [
			'solicitud' => [
         'codProvincia' => $codProvincia,
			]
		];

		try {
			$result = $this->ExecuteRequest('ConsultarLocalidadesPorProvincia', $req);
		} catch (Exception $e) {
			throw $e;
		}

		return $result;
	}

	/**
	 * Consultar último número de orden
	 * 
	 * Descripción: Retorna el último número de orden de CPE autorizado según número de sucursal.
	 * 
	 * 2.7.5
	*/
	public function getUltNroOrden($tipo, $sucursal) {
		$req = [
			'solicitud' => [
				'sucursal' => $sucursal,
				'tipoCPE' => $tipo,
			]
		];

		return $this->ExecuteRequest('consultarUltNroOrden', $req);
	}

	/**
	 * Autorizar CPE Automotor
	 * 
	 * Descripción: Solicitud de una nueva carta de porte del tipo automotor.
	 * 
	 * 2.7.20
	 */
	public function AutorizarCPEAutomotor($cuit, $tipo, $sucursal, $nroOrden)
	{
		$req = [
			'solicitud' => [
				'cabecera' => [
          'tipoCP' => $tipo,
          'cuitSolicitante' => $cuit,
					'sucursal' => $sucursal,
					'nroOrden' => $nroOrden,					
				],
        'origen' => [
          'productor' => [            
            'codProvincia' => 12,            
            'codLocalidad' => 6904,            
          ]
        ],
        'correspondeRetiroProductor' => 0,
        'esSolicitanteCampo' => 'S',
        'datosCarga' => [
					'codGrano' => 23,
					'cosecha' => 2021,
					'pesoBruto' => 110,
					'pesoTara' => 10,          
        ],
				'destino' => [
					'cuit' => 20111111112,
					'esDestinoCampo' => 'S',
					'codProvincia' => 12,
					'codLocalidad' => 6904,
				],
        'destinatario' => [
					'cuit' => 20111111112
				],    
				'transporte' => [
					'cuitTransportista' => 20120372913,	
          'cuitChofer' => 20120372913,
          'dominio' => 'AA501SC',
					'fechaHoraPartida' => \Carbon\Carbon::now()->addMinutes(1)->toDateTimeLocalString(),
					'kmRecorrer' => 500,
          'mercaderiaFumigada' => 0,
				],            
			]
		];

		try {
			$result = $this->ExecuteRequest('autorizarCPEAutomotor', $req);
		} catch (Exception $e) {      
			throw $e;
		}

		return $result;
	}

	/**
	 * Consultar CPE Automotor
	 * 
	 * Descripción: Busca una CPE existente según parámetros de búsqueda 
	 * y retorna información de la misma.
	 * 
	 * 2.7.21
	*/
	public function consultarCPEAutomotor($cuit, $tipo, $sucursal, $nroOrden, $ctg)
  {
    
		$req = [
			'solicitud' => [
        'cuitSolicitante' => $cuit,
				'cartaPorte' => [
					'tipoCPE' => $tipo,
					'sucursal' => $sucursal,
					'nroOrden' => $nroOrden,
				]
			]
		];
        if($ctg) {
            $req = [
              'solicitud' => [
                'nroCTG' => $ctg,
              ]
            ];      
          }

		try {
			$result = $this->ExecuteRequest('consultarCPEAutomotor', $req);
		} catch (Exception $e) {
			throw $e;
		}

		return $result;
	}  

	/**
	 * Autorizar CPE Ferroviaria 
	 * 
	 * Descripción: Solicitud de una nueva carta de porte del tipo ferroviaria.
	 * 
	 * 2.7.6
	 */  
	public function AutorizarCPEFerroviaria($sucursal, $nroOrden)
	{
		$req = [
			'solicitud' => [
				'cabecera' => [
					'sucursal' => $sucursal,
					'nroOrden' => $nroOrden,
					'planta' => 202770,
				],
				'correspondeRetiroProductor' => 0,			
        'intervinientes' => [
          'cuitIntermediario' => 20400000000,
          'cuitRemitenteComercialVentaPrimaria' => 27000000014,
          'cuitCorredorVentaPrimaria' => 20200000006,
        ],    
				'datosCarga' => [
					'codGrano' => 23,
					'cosecha' => 2021,
					'pesoBruto' => 110,
					'pesoTara' => 10,
				],            
				'destino' => [
					'cuit' => 30604456475,          
					'esDestinoCampo' => 'N',
					'codProvincia' => 1,
					'codLocalidad' => 6904,
					'planta' => 519370
				],
				'destinatario' => [
					'cuit' => 20111111112
				],
				'transporte' => [
					'cuitTransportista' => 27111141776,
					'cuitTransportistaTramo2' => 27111141776,
					'nroVagon' => 65412,
					'nroPrecinto' => 072021,
					'nroOperativo' => 1340721,
					'ramal' => [
						'codigo' => 99,
						'descripcion' => 'Oro Ramal - Ejemplo'
					],
					'fechaHoraPartidaTren' => \Carbon\Carbon::now()->addMinutes(1)->toDateTimeLocalString(),
					'kmRecorrer' => 500,
					'cuitPagadorFlete' => 30700869918,
					'mercaderiaFumigada' => 1
				],
			]
		];

		try {
			$result = $this->ExecuteRequest('autorizarCPEFerroviaria', $req);

		} catch (Exception $e) {
      dd($e->getMessage());
			throw $e;
		}

		return $result;
	}

	/**
	 * Consultar CPE Ferroviaria
	 * 
	 * Descripción: Busca una CPE existente según parámetros de búsqueda y retorna información de la misma.
	 * 
	 * 2.7.7
	 */
	public function consultarCPEFerroviaria($cuit, $tipo, $sucursal, $nroOrden, $ctg)
  {		
		$req = [
			'solicitud' => [
                'cuitSolicitante' => $cuit,
				'cartaPorte' => [
					'tipoCPE' => $tipo,
					'sucursal' => $sucursal,
					'nroOrden' => $nroOrden,
				]
			]
		];

        if($ctg) {
            $req = [
              'solicitud' => [
                'nroCTG' => $ctg,
              ]
            ];      
          }
		
		try {
			$result = $this->ExecuteRequest('consultarCPEFerroviaria', $req);
		} catch (Exception $e) {
			throw $e;
		}

		return $result;
	}

	/**
	 * Confirmación de Arribo de CPE
	 * 
	 * Descripción: Método para informar la confirmación de arribo.
	 * 
	 * 2.7.14
	*/
	public function confirmarArriboCPE($cuit, $tipo, $sucursal, $nroOrden) 
	{
		$req = [
			'solicitud' => [
				'cuitSolicitante' => $cuit,
				'cartaPorte' => [
					'tipoCPE' => $tipo,
					'sucursal' => $sucursal,
					'nroOrden' => $nroOrden
				]
			]
		];

		return $this->ExecuteRequest('confirmarArriboCPE', $req);
	}


	/**
	 * Rechazar CPE
	 * 
	 * Descripción: Método para informar el rechazo de una carta de porte existente.
	 * 
	 * 2.7.16
	*/
	public function rechazoCPE($cuit, $tipo, $sucursal, $nroOrden)
	{
		$req = [
			'solicitud' => [
				'cuitSolicitante' => $cuit,
				'cartaPorte' => [
					'tipoCPE' => $tipo,
					'sucursal' => $sucursal,
					'nroOrden' => $nroOrden
				]
			]
		];

		return $this->ExecuteRequest('rechazoCPE', $req);
	}

	/**
	 * Anulación de CPE
	 * 
	 * Descripción: Método para anular una CPE existente.
	 * 
	 * 2.7.11
	*/
	public function anularCPE($tipo, $sucursal, $nroOrden)
	{
		$req = [
			'solicitud' => [
        'cartaPorte' => [
          'tipoCPE' => $tipo,
          'sucursal' => $sucursal,
          'nroOrden' => $nroOrden
        ],
			]
		];

		return $this->ExecuteRequest('anularCPE', $req);
	}


  /**
   * ------------------------------------------------------------------------------------------
   * ------------------------------------------------------------------------------------------
   * ------------------------------------------------------------------------------------------
  **/



    /**
	 * Sends request to AFIP servers
	 * 
	 * @since 0.7
	 *
	 * @param string 	$operation 	SOAP operation to do 
	 * @param array 	$params 	Parameters to send
	 *
	 * @return mixed Operation results 
	 **/
	public function ExecuteRequest($operation, $params = array())
	{
		$params = array_replace($this->GetWSInitialRequest($operation), $params); 

		$results = parent::ExecuteRequest($operation, $params);

		$this->_CheckErrors($operation, $results);

		return $results/* ->{$operation.'Resp'} */;
	}

    /**
	 * Make default request parameters for most of the operations
	 * 
	 * @since 0.7
	 *
	 * @param string $operation SOAP Operation to do 
	 *
	 * @return array Request parameters  
	 **/
	private function GetWSInitialRequest($operation)
	{
		if ($operation == 'dummy') {
			return array();
		}

		$ta = $this->afip->GetServiceTA('wscpe');

		return array(
			'auth' => array( 
				'token' => $ta->token,
				'sign' 	=> $ta->sign,
				'cuitRepresentada' 	=> $this->afip->CUIT
				)
		);
	}

    /**
	 * Check if occurs an error on Web Service request
	 * 
	 * @since 0.7
	 *
	 * @param string 	$operation 	SOAP operation to check 
	 * @param mixed 	$results 	AFIP response
	 *
	 * @throws Exception if exists an error in response 
	 * 
	 * @return void 
	 **/
	private function _CheckErrors($operation, $results)
	{
		//$res = $results/* ->{$operation.'Resp'} */;
		$res = $results->respuesta;


		/* if ($operation == 'FECAESolicitar') {
			if (isset($res->FeDetResp->FECAEDetResponse->Observaciones) && $res->FeDetResp->FECAEDetResponse->Resultado != 'A') {
				$res->Errors = new StdClass();
				$res->Errors->Err = $res->FeDetResp->FECAEDetResponse->Observaciones->Obs;
			}
		} */

		if (isset($res->errores->error)) {
			$err = $res->errores->error;
			throw new Exception('('.$err->codigo.') '.$err->descripcion, $err->codigo);
		}
	}
}