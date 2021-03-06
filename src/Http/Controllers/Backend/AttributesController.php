<?php

declare(strict_types=1);

namespace Cortex\Attributable\Http\Controllers\Backend;

use Cortex\Fort\Models\User;
use Illuminate\Http\Request;
use Cortex\Attributable\Models\Attribute;
use Cortex\Attributable\DataTables\Backend\AttributesDataTable;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class AttributesController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'attributes';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return app(AttributesDataTable::class)->render('cortex/foundation::backend.pages.datatable', ['resource' => 'cortex/attributable::common.attributes']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->process($request, new Attribute());
    }

    /**
     * Update the given resource in storage.
     *
     * @param \Illuminate\Http\Request              $request
     * @param \Cortex\Attributable\Models\Attribute $attribute
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attribute $attribute)
    {
        return $this->process($request, $attribute);
    }

    /**
     * Delete the given resource from storage.
     *
     * @param \Cortex\Attributable\Models\Attribute $attribute
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Attribute $attribute)
    {
        $attribute->delete();

        return intend([
            'url' => route('backend.attributes.index'),
            'with' => ['warning' => trans('cortex/attributable::messages.attribute.deleted', ['attributeId' => $attribute->id])],
        ]);
    }

    /**
     * Show the form for create/update of the given resource.
     *
     * @param \Cortex\Attributable\Models\Attribute $attribute
     *
     * @return \Illuminate\Http\Response
     */
    public function form(Attribute $attribute)
    {
        $types = array_combine(app('rinvex.attributable.types')->toArray(), app('rinvex.attributable.types')->toArray());
        $entities = array_combine(app('rinvex.attributable.entities')->toArray(), app('rinvex.attributable.entities')->toArray());

        return view('cortex/attributable::backend.forms.attribute', compact('attribute', 'types', 'entities'));
    }

    /**
     * Process the form for store/update of the given resource.
     *
     * @param \Illuminate\Http\Request              $request
     * @param \Cortex\Attributable\Models\Attribute $attribute
     *
     * @return \Illuminate\Http\Response
     */
    protected function process(Request $request, Attribute $attribute)
    {
        // Prepare required input fields
        $input = $request->all();

        // Save attribute
        $attribute->fill($input)->save();

        return intend([
            'url' => route('backend.attributes.index'),
            'with' => ['success' => trans('cortex/attributable::messages.attribute.saved', ['attributeId' => $attribute->id])],
        ]);
    }
}
