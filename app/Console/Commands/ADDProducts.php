<?php

namespace App\Console\Commands;

use App\Http\Controllers\Version_1_1\ProductsController;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ADDProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add products description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
          $controller = new ProductsController();

        for ($inch = 35; $inch <= 150; $inch++) {
            $meter = round($inch * 0.0254, 2);
            $name = "قشاط 17 * {$inch} / {$meter}";

            // تجهيز الداتا كأنها من Request
            $request = Request::create('/fake-url', 'POST', [
                'name' => $name,
                'code' => '',
                'notes' => '',
                'invoice_id' => null,
                'category_id' => null,
                'date' => '',
                'price_in_dollar' => null,
                'price_in_sp' => $inch*550,
                'profit' => null,
                'sell_in_sp' => $inch*600,
                'sell_in_dollar' => null,
                'photo' => ''
            ]);

            // استدعاء الدالة
            $response = $controller->save($request);

            if ($response->getStatusCode() === 200) {
                $this->info("✅ أُضيف المنتج: $name");
            } else {
                $this->error("❌ فشل في الإضافة: $name");
            }
        }

        $this->info("🎉 انتهى تنفيذ الإرسال للمنتجات");
    }

      
    
    
}
