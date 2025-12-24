<div>
    <h2 class="text-2xl font-bold mb-4">Dashboard</h2>

    <div class="grid grid-cols-3 gap-4">

        <div class="bg-white p-4 shadow rounded">Total GRN: {{ $totalGrn ?? 0 }}</div>
        <div class="bg-white p-4 shadow rounded">Total GDN: {{ $totalGdn ?? 0 }}</div>
        <div class="bg-white p-4 shadow rounded">Bank Balance: {{ $bankBalance ?? '0.00' }}</div>

    </div>
</div>
