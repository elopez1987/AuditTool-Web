<?php

declare(strict_types=1);

use App\Http\Controllers\Receipt\ReceiptAsnsController;
use App\Repositories\Receipt\ReceiptAsnShowRepository;
use App\Services\Receipt\ReceiptAsnShowService;
use App\User;
use App\ViewModels\Receipt\ReceiptAsnShowViewModel;
use Illuminate\Database\Query\Grammars\SqlServerGrammar;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

beforeEach(function (): void {
    $this->prepareAuthenticationSchema();
    $userId = $this->localUser(['username' => 'receipt.operator', 'name' => 'Receipt Operator']);
    $this->actingAs(User::findOrFail($userId), 'web');

    Schema::create('receiptasn', function (Blueprint $table): void {
        $table->id();
        $table->uuid('uuid_asn');
        $table->string('centerid');
        $table->string('warehouseid');
        $table->string('statusid');
        $table->bigInteger('providerid')->nullable();
        $table->bigInteger('razonstatusid')->nullable();
        $table->bigInteger('stockcategoryid')->nullable();
        $table->string('shipmentid')->nullable();
    });
    Schema::create('systemcenterdistributions', function (Blueprint $table): void {
        $table->string('centerid')->primary();
        $table->string('description');
    });
    Schema::create('systemwarehouses', function (Blueprint $table): void {
        $table->string('warehouseid')->primary();
        $table->string('name');
    });
    foreach (['receiptproviders', 'storagereasons', 'inventorystockcategories'] as $name) {
        Schema::create($name, function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
        });
        DB::table($name)->insert(['id' => 1, 'name' => 'Test Name', 'description' => 'Test Description']);
    }
    Schema::create('receiptasntimes', function (Blueprint $table): void {
        $table->id();
        $table->bigInteger('receiptid');
        $table->dateTime('send_to_billing')->nullable();
    });
    Schema::create('receiptstock', function (Blueprint $table): void {
        $table->id();
        $table->bigInteger('receiptid');
        $table->string('partnumberid');
        $table->string('expirecode')->nullable();
        $table->double('quantity');
        $table->bigInteger('unitmeasureid')->nullable();
    });
    Schema::create('receiptcontainers', function (Blueprint $table): void {
        $table->id();
        $table->bigInteger('receiptid');
        $table->bigInteger('partnumberid');
        $table->integer('quantity');
    });
    Schema::create('inventorypartnumbers', function (Blueprint $table): void {
        $table->id();
        $table->string('prtnum');
        $table->string('lngdsc');
    });
    Schema::create('inventoryunitsmeasure', function (Blueprint $table): void {
        $table->id();
        $table->string('description');
    });
    Schema::create('storagecomments', function (Blueprint $table): void {
        $table->id();
        $table->bigInteger('modelid');
        $table->string('modeltype');
        $table->bigInteger('createuser')->nullable();
        $table->text('comment');
        $table->timestamps();
    });
    Schema::create('storageevidencephotos', function (Blueprint $table): void {
        $table->id();
        $table->bigInteger('model_id');
        $table->string('model_type');
        $table->bigInteger('createuser')->nullable();
        $table->string('auxiliary01');
        $table->string('path');
        $table->timestamps();
    });
    Schema::create('storagecategoryincidences', function (Blueprint $table): void {
        $table->bigInteger('categoryincid')->primary();
        $table->string('description');
    });
    Schema::create('storagecategoryincidencedetails', function (Blueprint $table): void {
        $table->bigInteger('categoryincdetailid')->primary();
        $table->string('description');
    });
    Schema::create('receiptincidencestock', function (Blueprint $table): void {
        $table->id();
        $table->bigInteger('receiptid');
        $table->bigInteger('partnumberid');
        $table->integer('quantity');
        $table->bigInteger('categoryid')->nullable();
        $table->bigInteger('categorydetailid')->nullable();
        $table->string('createuser')->nullable();
        $table->string('lpnid')->nullable();
        $table->dateTime('created_at')->nullable();
    });

    DB::table('securityaccesswarehouses')->insert([
        'username' => 'receipt.operator', 'centerid' => 'CENTER', 'warehouseid' => 'WAREHOUSE',
    ]);
    DB::table('systemcenterdistributions')->insert(['centerid' => 'CENTER', 'description' => 'Test Center']);
    DB::table('systemwarehouses')->insert(['warehouseid' => 'WAREHOUSE', 'name' => 'Test Warehouse']);
    DB::table('receiptasn')->insert([
        'id' => 1, 'uuid_asn' => 'a8d50b98-d09b-44a8-ab54-5a18449de916',
        'centerid' => 'CENTER', 'warehouseid' => 'WAREHOUSE', 'statusid' => 'PG',
        'providerid' => 1, 'razonstatusid' => 1, 'stockcategoryid' => 1, 'shipmentid' => 'ASN-001',
    ]);
    DB::table('receiptasntimes')->insert(['receiptid' => 1, 'send_to_billing' => '2026-01-01 10:00:00']);
    DB::table('inventorypartnumbers')->insert([
        ['id' => 1, 'prtnum' => 'SKU-001', 'lngdsc' => 'Material One'],
        ['id' => 2, 'prtnum' => 'SKU-002', 'lngdsc' => 'Material Two'],
    ]);
    DB::table('inventoryunitsmeasure')->insert(['id' => 1, 'description' => 'Pieces']);
    DB::table('receiptstock')->insert([
        ['id' => 1, 'receiptid' => 1, 'partnumberid' => '1', 'quantity' => 7, 'unitmeasureid' => 1, 'expirecode' => 'LOT-A'],
        ['id' => 2, 'receiptid' => 1, 'partnumberid' => '2', 'quantity' => 12.5, 'unitmeasureid' => 1, 'expirecode' => 'LOT-B'],
    ]);
    DB::table('receiptcontainers')->insert([
        ['receiptid' => 1, 'partnumberid' => 1, 'quantity' => 3],
        ['receiptid' => 1, 'partnumberid' => 1, 'quantity' => 4],
        ['receiptid' => 2, 'partnumberid' => 1, 'quantity' => 100],
    ]);
    DB::table('storagecategoryincidences')->insert(['categoryincid' => 1, 'description' => 'Damage']);
    DB::table('storagecategoryincidencedetails')->insert(['categoryincdetailid' => 1, 'description' => 'Packaging']);
    foreach (['DES', 'INC', 'OTHER'] as $type) {
        DB::table('storageevidencephotos')->insert([
            'model_id' => 1, 'model_type' => 'ReceiptAsn', 'createuser' => $userId,
            'auxiliary01' => $type, 'path' => $type.'.jpg', 'created_at' => '2026-01-01 10:00:00',
        ]);
    }
    DB::table('storagecomments')->insert([
        'modelid' => 1, 'modeltype' => 'receiptasn', 'createuser' => $userId,
        'comment' => '<script>unsafe()</script>', 'created_at' => '2026-01-01 10:00:00',
    ]);
    DB::table('receiptincidencestock')->insert([
        'receiptid' => 1, 'partnumberid' => 1, 'quantity' => 1, 'categoryid' => 1,
        'categorydetailid' => 1, 'createuser' => 'receipt.operator',
        'created_at' => '2026-01-01 10:00:00',
    ]);
});

it('aggregates scanned stock for the requested receipt and returns zero for missing scans', function (): void {
    $stocks = $this->app->make(ReceiptAsnShowRepository::class)->stocks(1);

    expect($stocks)->toHaveCount(2);
    expect((float) $stocks[0]->quantity)->toBe(7.0);
    expect((int) $stocks[0]->totalescaneado)->toBe(7);
    expect((float) $stocks[1]->quantity)->toBe(12.5);
    expect((int) $stocks[1]->totalescaneado)->toBe(0);
    expect($stocks[0]->unidadmedida)->toBe('Pieces');
});

it('preserves stock rows and the existing per-material aggregation when the material repeats', function (): void {
    DB::table('receiptstock')->insert([
        'receiptid' => 1, 'partnumberid' => '1', 'quantity' => 8, 'expirecode' => 'OTHER-LOT',
    ]);
    $stocks = $this->app->make(ReceiptAsnShowRepository::class)->stocks(1);

    expect($stocks)->toHaveCount(3);
    expect((int) $stocks[2]->totalescaneado)->toBe(7);
});

it('compiles a filtered aggregate join with the SQL Server grammar', function (): void {
    $connection = DB::connection();
    $original = $connection->getQueryGrammar();
    $connection->setQueryGrammar(new SqlServerGrammar($connection));
    try {
        $query = $this->app->make(ReceiptAsnShowRepository::class)->stockQuery(1);
        $sql = $query->toSql();
        expect($sql)->toContain('left join (select', '[receiptcontainers]', 'group by [receiptid], [partnumberid]');
        expect($sql)->not->toContain('`', 'limit ', 'group by [inventorypartnumbers]');
        expect($query->getBindings())->toBe([1, 1]);
    } finally {
        $connection->setQueryGrammar($original);
    }
});

it('loads the receipt by UUID with all detail relationships already available', function (): void {
    $details = $this->app->make(ReceiptAsnShowService::class)->details('a8d50b98-d09b-44a8-ab54-5a18449de916');
    $receipt = $details['receipt'];

    foreach (['center', 'warehouse', 'provider', 'reason', 'inventorystock', 'timereceipt'] as $relation) {
        expect($receipt->relationLoaded($relation))->toBeTrue();
    }
    expect($receipt->center->description)->toBe('Test Center');
    expect($receipt->timereceipt->send_to_billing)->not->toBeNull();
    expect($details['comments'])->toHaveCount(1);
    expect($details['evidences'])->toHaveCount(2);
    expect($details['incidences'][0]->user->name)->toBe('RECEIPT OPERATOR');
    expect($details['incidences'][0]->categoryincidence->description)->toBe('Damage');
});

it('does not load detail collections for a receipt outside the operator access', function (): void {
    DB::table('receiptasn')->where('id', 1)->update(['warehouseid' => 'NOT-ALLOWED']);
    DB::enableQueryLog();
    DB::flushQueryLog();

    expect($this->app->make(ReceiptAsnShowService::class)->details('a8d50b98-d09b-44a8-ab54-5a18449de916'))->toBeNull();
    expect(DB::getQueryLog())->toHaveCount(1);
    DB::disableQueryLog();
});

it('preserves the existing error redirect when the receipt UUID does not exist', function (): void {
    $this->withSession([]);
    $response = $this->app->make(ReceiptAsnsController::class)
        ->show('00000000-0000-0000-0000-000000000000');

    expect($response)->toBeInstanceOf(RedirectResponse::class);
    expect(session('error'))->toBe('No se encuentra el número de recibo solicitado.');
});

it('retains historical photo and comment authors after soft deletion', function (): void {
    DB::table('users')->update(['deleted_at' => '2026-01-02 10:00:00']);
    $repository = $this->app->make(ReceiptAsnShowRepository::class);

    expect($repository->comments(1)->first()->user?->name)->toBe('RECEIPT OPERATOR');
    expect($repository->evidences(1)->first()->user?->name)->toBe('RECEIPT OPERATOR');
});

it('keeps detail query counts constant as evidence comments and incidences grow', function (): void {
    $service = $this->app->make(ReceiptAsnShowService::class);
    DB::enableQueryLog();
    DB::flushQueryLog();
    $service->details('a8d50b98-d09b-44a8-ab54-5a18449de916');
    $initialCount = count(DB::getQueryLog());

    for ($index = 0; $index < 10; $index++) {
        $id = $this->localUser(['username' => 'author-'.$index, 'name' => 'Author '.$index]);
        DB::table('storagecomments')->insert([
            'modelid' => 1, 'modeltype' => 'ReceiptAsn', 'createuser' => $id, 'comment' => 'Comment',
        ]);
        DB::table('storageevidencephotos')->insert([
            'model_id' => 1, 'model_type' => 'ReceiptAsn', 'createuser' => $id,
            'auxiliary01' => 'DES', 'path' => 'photo.jpg',
        ]);
        DB::table('receiptincidencestock')->insert([
            'receiptid' => 1, 'partnumberid' => 1, 'quantity' => 1,
            'categoryid' => 1, 'categorydetailid' => 1, 'createuser' => 'author-'.$index,
        ]);
    }
    DB::flushQueryLog();
    $details = $service->details('a8d50b98-d09b-44a8-ab54-5a18449de916');
    expect(DB::getQueryLog())->toHaveCount($initialCount);

    DB::flushQueryLog();
    $data = $this->app->make(ReceiptAsnShowViewModel::class)->toArray($details);
    foreach (['coments', 'evidences', 'incidences'] as $key) {
        foreach ($data[$key] as $item) {
            expect($item->relationLoaded('user'))->toBeTrue();
            $item->user?->name;
        }
    }
    expect(DB::getQueryLog())->toBeEmpty();
    DB::disableQueryLog();
});

it('provides the data contract required by the restored original view', function (): void {
    $view = $this->app->make(ReceiptAsnsController::class)->show('a8d50b98-d09b-44a8-ab54-5a18449de916');
    expect($view->name())->toBe('Receipt.ReceiptAsn.show');
    $data = $view->getData();

    expect($data)->toHaveKeys(['receiptasn', 'stocks', 'coments', 'evidences', 'incidences']);
    expect($data['evidences'])->toHaveCount(2);
    expect((float) $data['stocks'][0]->quantitytrue)->toBe(7.0);
    expect((float) $data['stocks'][1]->quantitytrue)->toBe(12.5);
});

it('preserves receipt status for the original view action conditions', function (string $status): void {
    DB::table('receiptasn')->update(['statusid' => $status]);
    $details = $this->app->make(ReceiptAsnShowService::class)->details('a8d50b98-d09b-44a8-ab54-5a18449de916');
    $data = $this->app->make(ReceiptAsnShowViewModel::class)->toArray($details);

    expect($data['receiptasn']->statusid)->toBe($status);
})->with(['IG', 'CE']);

it('provides empty collections to the original view when no detail data exists', function (): void {
    DB::table('receiptstock')->delete();
    DB::table('storagecomments')->delete();
    DB::table('storageevidencephotos')->delete();
    DB::table('receiptincidencestock')->delete();
    $details = $this->app->make(ReceiptAsnShowService::class)->details('a8d50b98-d09b-44a8-ab54-5a18449de916');
    $data = $this->app->make(ReceiptAsnShowViewModel::class)->toArray($details);

    foreach (['stocks', 'coments', 'evidences', 'incidences'] as $key) {
        expect($data[$key])->toBeEmpty();
    }
});
