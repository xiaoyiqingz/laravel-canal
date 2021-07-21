<?php

namespace LaravelCanal;

use Com\Alibaba\Otter\Canal\Protocol\RowChange;

class MessageParse
{
    protected $message;

    /**
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    public function all()
    {
        $data = [];
        if (is_null($this->message->getEntries())) {
            return $data;
        }

        foreach ($this->message->getEntries() as $entry) {
            $data[] = [
                'entryType' => $entry->getEntryType(),
                'header' => $this->parseHeader($entry),
                'rowChange' => $this->parseRowChange($entry),
            ];
        }

        return $data;
    }

    public function parseHeader($entry)
    {
        $header = $entry->getHeader();

        return [
            'logFileName' => $header->getLogfileName(),
            'logFileOffset' => $header->getLogfileOffset(),
            'schemaName' => $header->getSchemaName(),
            'eventType' => $header->getEventType(),
            'executeTime' => $header->getExecuteTime(),
        ];
    }

    public function parseRowChange($entry)
    {
        $rowChange = new RowChange();
        $rowChange->mergeFromString($entry->getStoreValue());

        return [
            'isDdl' => $rowChange->getIsDdl(),
            'sql' => $rowChange->getSql(),
            'eventType' => $rowChange->getEventType(),
            'rowDatas' => $this->parseRowDatas($rowChange),
        ];
    }

    public function parseRowDatas($rowChange)
    {
        $rowDatas = [];
        foreach ($rowChange->getRowDatas() as $rowData) {
            $rowDatas[] = [
                'beforeColumns' => $this->parseColumns($rowData->getBeforeColumns()),
                'afterColumns' => $this->parseColumns($rowData->getAfterColumns()),
            ];
        }

        return $rowDatas;
    }

    public function parseColumns($columns)
    {
        $datas = [];
        foreach ($columns as $column) {
            $datas[$column->getName()] = [
                'index' => $column->getIndex(),
                'name' => $column->getName(),
                'value' => $column->getValue(),
                'isNull' => $column->getIsNull(),
                'mysqlType' => $column->getMysqlType(),
                'isPrimary' => $column->getIsKey(),
                'updated' => $column->getUpdated(),
                'length' => $column->getLength(),
            ];
        }

        return $datas;
    }

    public function getRawMessage()
    {
        return $this->message;
    }
}
