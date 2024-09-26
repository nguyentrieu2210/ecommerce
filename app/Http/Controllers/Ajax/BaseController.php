<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Psy\TabCompletion\Matcher\FunctionsMatcher;

class BaseController extends Controller
{
    //
    public function changePublish(Request $request) {
        $instanceRepository = $this->setupModelRepository($request->model);
        $object = app($instanceRepository)->findById($request->id);
        $object->update(['publish' => $request->data]);
        if($request->model == 'userCatalogue') {
            $relations = preg_replace('/Catalogue$/', 's', $request->model);
            $object->{$relations}()->update(['publish' => $request->data]);
        }
        return response()->json(['message' => 'Cập nhật trạng thái hoạt động bản ghi thành công']);
    }

    public function changeSupervisor(Request $request) {
        $instanceRepository = $this->setupModelRepository($request->model);
        $object = app($instanceRepository)->findById($request->id);
        $object->update(['user_id' => $request->data]);
        return response()->json(['message' => 'Cập nhật thông tin nhà cung cấp thành công']);
    }

    public function changeMultiplePublish(Request $request) {
        $instanceRepository = $this->setupModelRepository($request->model);
        app($instanceRepository)->updateByWhereIn('id', $request->arrId, ['publish' => $request->data]);
        if($request->model == 'userCatalogue') {
            $relations = preg_replace('/Catalogue/', '', $request->model);
            $instanceRelation = $this->setupModelRepository($relations);
            app($instanceRelation)->updateByWhereIn('user_catalogue_id', $request->arrId, ['publish' => $request->data]);
        }

        return response()->json(['message' => 'Cập nhật trạng thái hoạt động của các bản ghi thành công']);
    }

    private function setupModelRepository ($model = "") {
        return 'App\Repositories\Interfaces\\' . ucfirst($model) . 'RepositoryInterface';
    }

    private function setupModelService ($model = "") {
        return 'App\Services\Interfaces\\' . ucfirst($model) . 'ServiceInterface';
    }

    public function searchModel(Request $request) {
        $instanceRepository = $this->setupModelRepository($request->model);
        $keyword = '%'.$request->keyword.'%';
        $relations = $request->relation !== null ? $request->relation : [];
        $condition = [
            ['name', 'LIKE', $keyword]
        ];
        if($request->model !== 'province') {
            $condition[] = ['publish', '=', 2];
        }
        $data = app($instanceRepository)->findByCondition($condition, [], '', [], '', [], $relations)->toArray();
        return response()->json(['data' => $data, 'message' => 'get data success']);
    }

    public function getDataModel(Request $request) {
        $instanceRepository = $this->setupModelRepository($request->model);
        if(!isset($request->isCondition)) {
            $condition = [
                ['publish', '=', 2]
            ];
        }else{
            $condition = [];
        }
        $data = app($instanceRepository)->findByCondition($condition)->toArray();
        return response()->json(['data' => $data, 'message' => 'get data success']);
    }

    public function getDataProductByWarehouse(Request $request) {
        $instanceRepository = $this->setupModelRepository($request->model);
        $data = app($instanceRepository)->getProductByWarehouse($request->warehouse_id, $request->keyword, $request->fieldSearchs)->toArray();
        return response()->json(['data' => $data, 'message' => 'get data success']);
    }

    public function createModel(Request $request) {
        $payload['name'] = $request->name;
        $payload['code'] = $request->code;
        $payload['value'] = $request->value;
        $instanceRepository = $this->setupModelRepository($request->model);
        $model = app($instanceRepository)->create($payload);
        $data = app($instanceRepository)->getAll();
        return response()->json(['new' => $model, 'data' => $data]);
    }

    public function getRelationById (Request $request) {
        $instanceRepository = $this->setupModelRepository($request->model);
        $relation = $request->relation;
        $data = app($instanceRepository)->findById($request->id, $request->relation)->{$relation};
        return response()->json(['data' => $data]);
    }

    public function changeStatus(Request $request) {
        $instanceService = $this->setupModelService($request->model);
        app($instanceService)->{$request->type}($request->id, $request);
        return response()->json(['message' => $request->message]);
    }

    //use to call a method in service
    public function callMethodService(Request $request) {
        $instanceService = $this->setupModelService($request->model);
        app($instanceService)->{$request->method}($request->id, $request);
        // return response()->json(['message' => $request->message]);
    }

    public function viewMore(Request $request) {
        $instanceRepository = $this->setupModelRepository($request->model);
        $data = app($instanceRepository)->findByConditionPivot([
            ['id', '=', $request->id]
        ], $request->relation, [], [], [
            __('config.conditionPublish')
        ], [], $request->orderBy, $request->subRelation, $request->paginate);
        return response()->json($data);
    }

    //FRONTEND
    public function createComment (Request $request) {
        $model = 'comment';
        $payload = [
            'star_rating' => $request->input('star_rating'),
            'content' => $request->input('content'),
            'customer_id' => $request->input('customer_id'),
            'model' => $request->input('model'),
            'model_id' => $request->input('model_id'),
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
        ];
        if ($request->hasFile('files')) {
            $uploadedFiles = $request->file('files');
            $imagePaths = [];

            foreach ($uploadedFiles as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->move(public_path('uploads'), $filename);
                $imagePath = 'uploads/' . $filename;
                $imagePaths[] = $imagePath;
            }
        }
        if(count($imagePaths)) {
            $payload['album'] = $imagePaths;
        }
        $instanceRepository = $this->setupModelRepository($model);
        app($instanceRepository)->create($payload);
        return response()->json(['message' => 'success']);
    }

    public function getComment (Request $request) {
        $model = 'comment';
        $instanceRepository = $this->setupModelRepository($model);
        $condition = [
            ['model', '=', $request->model],
            __('config.conditionPublish'),
            ['model_id', '=', $request->model_id],
        ];
        if(isset($request->star_rating)) {
            $condition[] = ['star_rating', '=', $request->star_rating];
        }
        if(isset($request->album)) {
            $condition[] = ['album', '<>', null];
        }
        $limit = isset($request->limit) ? $request->limit : null;
        $data = app($instanceRepository)->findByCondition($condition, ['id', 'DESC'], '', [], '', [], ['customers'], $limit)->toArray();
        return response()->json(['message' => 'success', 'data' => $data]);
    }

    public function updateModel (Request $request) {
        $model = $request->model;
        $instanceRepository = $this->setupModelRepository($model);
        app($instanceRepository)->updateById($request->id, $request->data);
        return response()->json(['message' => 'success']);
    }
}
