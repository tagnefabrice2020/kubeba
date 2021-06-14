
<?php 

namespace App\Traits;


trait ApiResponser {

	private function successResponse($data, $code) 
	{
		return response()->json($data, $code);
	}

	protected function errorResponse($message, $code)
	{
		return response()->json(['error' => $message, 'code' => $code], $code);
	}

	protected function showAll($collection, $code = 200)
	{
		return $this->successResponse(['data' => $collection], $code);
	}

	protected function showOne($collection, $code = 200)
	{
		return $this->successResponse(['data' => $collection], $code);
	}

	protected function showSuccessMessage($message, $code=200)
	{
		return response()->json([
			'success' => true,
			'message' => $message,
		], $code);
	}

	protected function showErrorMessage($message, $code=500)
	{
		return response()->json([
			'error' => true,
			'message' => $message,
		], $code);
	}

}