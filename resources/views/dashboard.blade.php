<x-layouts::app :title="__('Dashboard')">

    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-4xl font-extrabold bg-gradient-to-r from-pink-500 via-fuchsia-500 to-rose-500 bg-clip-text text-transparent">
                Beauty Inventory Dashboard
            </h1>

            <p class="mt-2 text-zinc-500 dark:text-zinc-400">
                Manage beauty products, categories, suppliers, and users.
            </p>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

            <div class="rounded-2xl bg-gradient-to-r from-pink-500 to-rose-400 p-6 shadow-lg">
                <p class="text-pink-100 text-sm">Categories</p>
                <h2 class="mt-2 text-4xl font-bold text-white">5</h2>
            </div>

            <div class="rounded-2xl bg-gradient-to-r from-fuchsia-500 to-pink-500 p-6 shadow-lg">
                <p class="text-pink-100 text-sm">Products</p>
                <h2 class="mt-2 text-4xl font-bold text-white">20</h2>
            </div>

            <div class="rounded-2xl bg-gradient-to-r from-purple-500 to-pink-500 p-6 shadow-lg">
                <p class="text-pink-100 text-sm">Suppliers</p>
                <h2 class="mt-2 text-4xl font-bold text-white">4</h2>
            </div>

            <div class="rounded-2xl bg-gradient-to-r from-rose-500 to-pink-400 p-6 shadow-lg">
                <p class="text-pink-100 text-sm">Users</p>
                <h2 class="mt-2 text-4xl font-bold text-white">2</h2>
            </div>

        </div>

        {{-- Recent Products --}}
        <div class="rounded-2xl border border-pink-200 bg-white p-6 shadow-lg dark:border-pink-900 dark:bg-zinc-900">

            <h2 class="mb-5 text-2xl font-bold text-pink-500">
                Recent Products
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full">

                    <thead>
                        <tr class="border-b border-pink-200 dark:border-pink-900">
                            <th class="py-3 text-left text-pink-500">Product</th>
                            <th class="py-3 text-left text-pink-500">Category</th>
                            <th class="py-3 text-left text-pink-500">Stock</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr class="border-b border-zinc-200 dark:border-zinc-700">
                            <td class="py-4">Glow Serum</td>
                            <td>Skincare</td>
                            <td>
                                <span class="rounded-full bg-green-100 px-3 py-1 text-green-600">
                                    25
                                </span>
                            </td>
                        </tr>

                        <tr class="border-b border-zinc-200 dark:border-zinc-700">
                            <td class="py-4">Lip Tint</td>
                            <td>Makeup</td>
                            <td>
                                <span class="rounded-full bg-green-100 px-3 py-1 text-green-600">
                                    18
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td class="py-4">Face Wash</td>
                            <td>Skincare</td>
                            <td>
                                <span class="rounded-full bg-green-100 px-3 py-1 text-green-600">
                                    30
                                </span>
                            </td>
                        </tr>

                    </tbody>

                </table>
            </div>

        </div>

        {{-- Information Cards --}}
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

            <div class="rounded-2xl bg-white p-6 shadow-lg dark:bg-zinc-900">

                <h2 class="mb-4 text-xl font-bold text-pink-500">
                    Top Categories
                </h2>

                <ul class="space-y-3">
                    <li class="flex justify-between">
                        <span>Skincare</span>
                        <span class="font-semibold text-pink-500">45%</span>
                    </li>

                    <li class="flex justify-between">
                        <span>Makeup</span>
                        <span class="font-semibold text-pink-500">35%</span>
                    </li>

                    <li class="flex justify-between">
                        <span>Haircare</span>
                        <span class="font-semibold text-pink-500">20%</span>
                    </li>
                </ul>

            </div>

            <div class="rounded-2xl bg-white p-6 shadow-lg dark:bg-zinc-900">

                <h2 class="mb-4 text-xl font-bold text-pink-500">
                    Inventory Status
                </h2>

                <div class="space-y-4">

                    <div>
                        <div class="mb-1 flex justify-between">
                            <span>Products Available</span>
                            <span>80%</span>
                        </div>
                        <div class="h-3 rounded-full bg-zinc-200">
                            <div class="h-3 w-4/5 rounded-full bg-pink-500"></div>
                        </div>
                    </div>

                    <div>
                        <div class="mb-1 flex justify-between">
                            <span>Low Stock</span>
                            <span>15%</span>
                        </div>
                        <div class="h-3 rounded-full bg-zinc-200">
                            <div class="h-3 w-[15%] rounded-full bg-yellow-500"></div>
                        </div>
                    </div>

                    <div>
                        <div class="mb-1 flex justify-between">
                            <span>Out of Stock</span>
                            <span>5%</span>
                        </div>
                        <div class="h-3 rounded-full bg-zinc-200">
                            <div class="h-3 w-[5%] rounded-full bg-red-500"></div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</x-layouts::app>