<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};

use App\Models\Packet;
use App\Repositories\Packet\PacketRepository;
use App\Http\Resources\Packet\{PacketCollection, PacketResource};
use App\Http\Requests\Packet\{CreatePacketRequest, UpdatePacketRequest};

class PacketController extends Controller
{

    public function index(PacketRepository $packetRepo, Request $request): PacketCollection
    {
        $packets = $packetRepo->get($request->take, $request->name, $request->sort_by, $request->order_by ?? 'asc');

        return new PacketCollection($packets);
    }

    public function store(CreatePacketRequest $request): PacketResource
    {
        $packet = Packet::create($request->all());

        return new PacketResource($packet);
    }

    public function show(Packet $packet): PacketResource
    {
        return new PacketResource($packet);
    }

    public function update(UpdatePacketRequest $request, Packet $packet): PacketResource
    {
        $packet->update($request->all());

        return new PacketResource($packet);
    }

    public function destroy(Packet $packet): JsonResponse
    {
        $packet->delete();

        return response()->json(['message' => 'Paket dihapus']);
    }
}
