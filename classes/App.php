<?php
class App
{
    protected array $operations = ['+', '-', '*'];
    protected int $calculations = 0;
    protected array $lines = [];
    protected array $notifications = [];

    public function init(): self
    {
        $input = $this->loadInput();

        if (empty($input)) {
            return $this;
        }

        // reset variables
        $this->setCalculations();
        $this->setLines();
        $this->setNotifications();

        $lines = preg_split('/\R/', trim($input));

        if (is_array($lines) && !empty($lines)) {
            // load new values
            $valid_lines = [];

            foreach ($lines as $line) {
                if (!empty(trim($line)) && $this->isValidLine($line)) {
                    $valid_lines[] = trim($line);
                }
            }

            $this->setLines($valid_lines);

            $line_count = (int) array_shift($lines);

            $this->setCalculations($line_count);
        }

        return $this;
    }

    protected function loadInput(): string
    {
        $input = null;

        if (defined('STDIN')) {
            $input = stream_get_contents(STDIN);
        }

        if (!empty($input)) {
            return $input;
        }

        $inputFile = __DIR__ . '/../input.txt';

        if (is_file($inputFile)) {
            return file_get_contents($inputFile);
        }

        return '';
    }

    protected function isValidLine(string $line): bool
    {
        return preg_match('/^\d+(\D)\d+$/', $line) === 1;
    }

    /**
     * @param int $calculations
     * @return App
     */
    public function setCalculations(int $calculations = 0): self
    {
        $this->calculations = $calculations;

        return $this;
    }

    /**
     * @return int
     */
    public function getCalculations(): int
    {
        return $this->calculations;
    }

    /**
     * @param array $lines
     * @return App
     */
    public function setLines(array $lines = []): self
    {
        $this->lines = $lines;

        return $this;
    }

    /**
     * @return array
     */
    public function getLines(): array
    {
        return $this->lines;
    }

    public function getLine(int $key = 0): ?string
    {
        return $this->lines[$key] ?? '';
    }

    public function getPreparedLine(int $key = 0): ?array
    {
        $output = [];

        $line = $this->getLine($key);

        if (!empty($line)) {
            $parts = $this->splitLine($line);

            if (!in_array($parts['action'], $this->operations, true)) {
                $parts['valid'] = false;
            }

            $output = $parts;
        }

        return $output;
    }

    protected function splitLine(string $line): array
    {
        preg_match('/^(\d+)(\D)(\d+)$/', $line, $matches);

        return [
            'a' => $matches[1],
            'b' => $matches[3],
            'action' => $matches[2],
            'valid' => true,
        ];
    }

    public function invalidInput(): bool
    {
        return $this->calculations !== count($this->lines);
    }

    /**
     * @param array $notifications
     * @return App
     */
    public function setNotifications(array $notifications = []): self
    {
        $this->notifications = $notifications;

        return $this;
    }

    /**
     * @param string $type
     * @param string $message
     * @return App
     */
    public function addNotification(string $type, string $message): self
    {
        $this->notifications[] = [
            'type' => $type,
            'message' => $message,
        ];

        return $this;
    }

    public function hasNotifications(): bool
    {
        return count($this->notifications) > 0;
    }

    /**
     * @return array
     */
    public function getNotifications(): array
    {
        return $this->notifications;
    }
}