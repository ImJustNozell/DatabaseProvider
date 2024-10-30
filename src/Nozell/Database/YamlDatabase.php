<?php

namespace Nozell\Database;

use RuntimeException;

class YamlDatabase extends AbstractDatabase
{
    protected function loadFromFile(): array
    {
        if (file_exists($this->filePath)) {
            $data = yaml_parse_file($this->filePath);
            if ($data === false) {
                throw new RuntimeException("Error al leer el archivo YAML.");
            }
            return $data ?: [];
        }
        return [];
    }

    protected function saveToFile(array $data): void
    {
        if (yaml_emit_file($this->filePath, $data, YAML_UTF8_ENCODING) === false) {
            throw new RuntimeException("Error al escribir en el archivo YAML.");
        }
    }

    protected function saveEntry(string $section, string $key, $value): void
    {
        $data = $this->loadFromFile();
        $data[$section][$key] = $value;
        $this->saveToFile($data);
    }

    protected function getEntry(string $section, string $key)
    {
        $data = $this->loadFromFile();
        return $data[$section][$key] ?? null;
    }

    protected function deleteEntry(string $section, string $key): void
    {
        $data = $this->loadFromFile();
        unset($data[$section][$key]);
        $this->saveToFile($data);
    }
}
