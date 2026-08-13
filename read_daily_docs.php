<?php
// 2026-08-13 03:39:45
/*
**Topic Name:** Implementing Relationships in Laravel Eloquent

**Explanation:** In Laravel, Eloquent provides an elegant way to interact with your database using objects. One of the essential features of Eloquent is its ability to implement relationships between models. This topic will cover how to create one-to-one, one-to-many, and many-to-many relationships in Laravel.

**Code Example:**

Let's say we have three tables: `users`, `orders`, and `order_items`. We want to establish relationships between these tables.

First, let's define our models:
```php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

// app/Models/OrderItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        // assuming a Products table exists
        // and we have a relationship defined
        return $this->belongsTo(Product::class);
    }
}
```

Next, let's create a relationship in the controller:
```php
// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;

class OrderController extends Controller
{
    public function show(Order $order, User $user)
    {
        $user->load('orders'); // load all orders for the user
        $order->load('items'); // load all items for the order

        return view('orders.show', [
            'order' => $order,
            'user' => $user,
        ]);
    }
}
```

Finally, in our view, we can access the relationships like this:
```php
// resources/views/orders/show.blade.php

{!! $user->orders->each(function($order) {
    echo $order->items->each(function($item) {
        echo $item->product->name;
    });
}) !!}
```

In this example, we've established one-to-many relationships (`orders` on the `User` model, `items` on the `Order` model) and a many-to-many relationship (through the `order_items` table). We've also demonstrated how to load relationships using Eloquent.
*/
