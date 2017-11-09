<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Admin\LanguageService;
use App\Http\Requests\LanguageCreateRequest;
use App\Http\Requests\LanguageUpdateRequest;


class LanguageController extends Controller
{

    /**
     * @var languageService
     */
    protected $languageService;

    /**
     * @var LanguageValidator
     */
    protected $validator;

    public function __construct( LanguageService $languageService )
    {
        $this->languageService = $languageService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * ajax 获取数据
     * @author Yusure  http://yusure.cn
     * @date   2017-11-03
     * @param  [param]
     * @return [type]     [description]
     */
    public function ajaxIndex()
    {
        $responseData = $this->languageService->ajaxIndex();
        return response()->json( $responseData );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  LanguageCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(LanguageCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $language = $this->repository->create($request->all());

            $response = [
                'message' => 'Language created.',
                'data'    => $language->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $language = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $language,
            ]);
        }

        return view('languages.show', compact('language'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $language = $this->repository->find($id);

        return view('languages.edit', compact('language'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  LanguageUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(LanguageUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $language = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Language updated.',
                'data'    => $language->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Language deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Language deleted.');
    }
}
