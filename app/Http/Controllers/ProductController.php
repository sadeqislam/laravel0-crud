<?php
  
namespace App\Http\Controllers;
  
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; //storage include korar jonno ata use, image upate korle jeknae storage kota ta likte hy
use Illuminate\Http\Response;
use Illuminate\View\View;

  
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $products = Product::latest()->paginate(5);
        
        return view('products.index',compact('products'))
                    ->with('i', (request()->input('page', 1) - 1) * 5);
    }
  
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('products.create');
    }
  
    /**
     * Store a newly created resource in storage.
    //  */
    

public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => 'required|string|max:255',
        'quantity' => 'required|numeric',
        'price' => 'required|numeric',
        'detail' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    if ($image = $request->file('image')) {
        $destinationPath = 'images/';
        $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
        $image->move($destinationPath, $profileImage);

        // Assign the image path to the 'image' attribute in the request data
        $requestData = $request->all();
        $requestData['image'] = $profileImage;

        // Create the product using the updated request data with the image path
        Product::create($requestData);
    }

    return redirect()->route('products.index')
                    ->with('success', 'Product created successfully.');
}

  
    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        return view('products.show',compact('product'));
    }
  
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        return view('products.edit',compact('product'));
    }
  
    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, Product $product): RedirectResponse
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'quantity'=>'required|numeric',
    //         'price'=>'required|numeric',
    //         'detail' => 'required|string|max:255',
    //         'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //     ]);
        
    //   if ($image = $request->file('image')) {
    //     $destinationPath = 'images/';
    //     $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
    //     $image->move($destinationPath, $profileImage);

    //     // Assign the image path to the 'image' attribute in the request data
    //     $requestData = $request->all();
    //     $requestData['image'] = $profileImage;

    //     // Create the product using the updated request data with the image path
    //     Product::create($requestData);
    // }

    // else{
    //         unset($requestData['image']);
    //     }
        
    //     return redirect()->route('products.index')
    //                     ->with('success','Product updated successfully');
    // }


 public function update(Request $request, Product $product): RedirectResponse
{
    $request->validate([
        'name' => 'required|string|max:255',
        'quantity' => 'required|numeric',
        'price' => 'required|numeric',
        'detail' => 'required|string|max:255',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Retrieve the existing product
    $product = Product::findOrFail($product->id);

    if ($image = $request->file('image')) {
        // Delete the existing image if it exists
        if ($product->image) {
            Storage::delete('images/' . $product->image);
        }

        // Upload the new image
        $destinationPath = 'images/';
        $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
        $image->move($destinationPath, $profileImage);

        // Update the product data, including the new image path
        $product->update([
            'name' => $request->input('name'),
            'quantity' => $request->input('quantity'),
            'price' => $request->input('price'),
            'detail' => $request->input('detail'),
            'image' => $profileImage,
        ]);
    } else {
        // If no new image is provided, update other fields only
        $product->update([
            'name' => $request->input('name'),
            'quantity' => $request->input('quantity'),
            'price' => $request->input('price'),
            'detail' => $request->input('detail'),
        ]);
    }

    return redirect()->route('products.index')->with('success', 'Product updated successfully');
}




  
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
         
        return redirect()->route('products.index')
                        ->with('success','Product deleted successfully');
    }
}