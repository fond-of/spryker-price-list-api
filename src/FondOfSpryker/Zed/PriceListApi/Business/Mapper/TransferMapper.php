<?php

namespace FondOfSpryker\Zed\PriceListApi\Business\Mapper;

use Generated\Shared\Transfer\PriceListApiTransfer;

class TransferMapper implements TransferMapperInterface
{
    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\PriceListApiTransfer
     */
    public function toTransfer(array $data): PriceListApiTransfer
    {
        $priceListTransfer = new PriceListApiTransfer();

        return $priceListTransfer->fromArray($data, true);
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\PriceListApiTransfer[]
     */
    public function toTransferCollection(array $data): array
    {
        $transferCollection = [];

        foreach ($data as $itemData) {
            $transferCollection[] = $this->toTransfer($itemData);
        }

        return $transferCollection;
    }
}
