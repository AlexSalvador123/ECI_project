<template>
    <div class="container mx-auto p-4">
        <div class="text-center mb-8">
        <button @click="import_prices" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">
            Click to Import
        </button>
        <p class="mt-2 text-gray-700">{{ value }}</p>
        </div>

        <div class="mb-8 mx-auto">
            <h2 class="font-bold text-lg mb-2 text-center">Enter Account (optional):</h2>
            <div class="flex justify-center ">
                <input
                    type="text"
                    v-model="accountId"
                    placeholder="Enter Account"
                    class="border border-gray-300 rounded w-1/3 p-2 mr-5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>
        </div>
        <div class="mb-8">
            <h2 class="font-bold text-lg mb-2 text-center">Enter Product Codes:</h2>
            <div v-for="(code, index) in productCodes" :key="index" class="flex items-center justify-center mb-2">
                <input
                    type="text"
                    v-model="productCodes[index]"
                    placeholder="Enter Product Code"
                    class="border border-gray-300 rounded w-1/3 p-2 ml-5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <button @click="removeProductCode(index)" class="ml-2 bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-full shadow">
                    X
                </button>
            </div>
            <div class="text-center">
                <button @click="addProductCode" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-full shadow mr-2 m-2">
                    Add
                </button>
            </div>
            <div class="text-center mt-6">
                <button @click="fetchPrices" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                    Get Prices
                </button>
            </div>
        </div>

        <div class="flex justify-center">
            <div class="w-3/4" v-if="prices">
                <table class="min-w-full border border-gray-200 rounded shadow-lg overflow-hidden">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 p-4 text-left font-medium text-gray-700">Product</th>
                            <th class="border border-gray-300 p-4 text-left font-medium text-gray-700">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(value, key) in prices" :key="key" class="hover:bg-gray-50">
                            <td class="border border-gray-300 p-4 text-gray-600">{{ key }}</td>
                            <td class="border border-gray-300 p-4 text-gray-600">{{ value === null ? 'This product does not exist' : Math.round(value * 100) / 100 + '$' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-else>
            <p class="text-gray-600">There's no product codes</p>
            </div>
        </div>
    </div>
  </template>

  <script>
  import axios from 'axios';

  export default {
    data() {
      return {
        value: '',
        prices: null,
        productCodes: [''],
        accountId: null,
      };
    },
    methods: {
      addProductCode() {
        this.productCodes.push('');
      },
      removeProductCode(index) {
        this.productCodes.splice(index, 1);
      },
      async import_prices() {
        this.value = 'Processing...';
        try {
          let response= await axios.post('/import');
          this.value = 'Done';
          console.log(response.data);
        } catch (error) {
          console.error('error', error);
        }
      },
      async fetchPrices() {
        try {
          const response = await axios.get('/getPrice', {
            params: {
              productCodes: this.productCodes,
              accountId: this.accountId,
            },
          });
          console.log(response.data);
          this.prices = response.data;
        } catch (error) {
          console.error('error', error);
        }
      },
    },
  };
  </script>

  <style>
  .container {
    max-width: 800px;
    margin: auto;
  }
  </style>
