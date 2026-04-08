<template>
  <section class="page">
    <h1>Backend unit tests</h1>
    <hr>
    <p>
      This page describes the PHPUnit tests created for the backend refactor and shows the latest
      successful run output.
    </p>

    <h2>What is covered</h2>
    <div class="card">
      <h3>DateRangeTest</h3>
      <p>Validates date overlap rules.</p>
      <ul>
        <li><code>testOverlapDaysIncludesEndDate</code>: overlap includes the final day.</li>
        <li><code>testOverlapDaysReturnsZeroWhenNoOverlap</code>: disjoint ranges return 0 days.</li>
      </ul>
    </div>

    <div class="card">
      <h3>OverlapCalculatorTest</h3>
      <p>Validates employee pair aggregation logic.</p>
      <ul>
        <li><code>testCalculateTopPairReturnsExpectedPair</code>: returns correct pair and day count.</li>
        <li><code>testCalculateTopPairReturnsNullWhenNoPairs</code>: no common projects returns no pair.</li>
      </ul>
    </div>

    <div class="card">
      <h3>CsvRecordReaderTest</h3>
      <p>Validates CSV input rules and record parsing.</p>
      <ul>
        <li><code>testReadThrowsForInvalidHeaders</code>: rejects unexpected CSV headers.</li>
        <li><code>testReadThrowsForInvalidDataType</code>: rejects invalid data types/dates.</li>
        <li><code>testReadParsesValidRows</code>: maps valid rows into domain records.</li>
      </ul>
    </div>

    <div class="card">
      <h3>UploadControllerTest</h3>
      <p>Validates HTTP-level behavior and error handling.</p>
      <ul>
        <li><code>testHandleReturnsErrorWhenFileIsMissing</code></li>
        <li><code>testHandleReturnsErrorWhenFileTypeIsInvalid</code></li>
        <li><code>testHandleReturnsResultForValidCsv</code></li>
        <li><code>testHandleReturnsNoPairError</code></li>
      </ul>
    </div>

    <h2>Latest test run output</h2>
    <pre class="output">PHPUnit 10.5.63 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.2.12
Configuration: backend/phpunit.xml

...........                                                       11 / 11 (100%)

Time: 00:00.116, Memory: 8.00 MB

OK (11 tests, 24 assertions)</pre>
  </section>
</template>

<style scoped>
.page {
  max-width: 980px;
}

.card {
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 12px 14px;
  margin-bottom: 12px;
}

.card h3 {
  margin: 0 0 8px;
}

.output {
  background: #111827;
  color: #e5e7eb;
  padding: 14px;
  border-radius: 8px;
  overflow-x: auto;
}
</style>
