<template>
    <div class="w-full md:w-100 flex flex-col items-center h-25 mx-auto">
        <div class="w-full px-4">
            <div class="flex flex-col items-center relative">
                <div class="w-full  ">
                    <div class="my-2 p-1 flex border border-gray-200 bg-white rounded ">
                        <div class="flex flex-auto flex-wrap">
                            <div
                                v-for="option in selectedOptions"
                                :key="option.code"
                                class="flex justify-center items-center m-1 font-medium py-1 px-2 bg-white rounded-full text-sky-700 bg-sky-100 border border-sky-300 "
                            >
                                <div class="text-xs font-normal leading-none max-w-full flex-initial">{{ option.option
                                }}</div>
                                <div class="flex flex-auto flex-row-reverse">
                                    <div @click="selectOption(option)">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="100%"
                                            height="100%"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="feather feather-x cursor-pointer hover:text-sky-400 rounded-full w-4 h-4 ml-2"
                                        >
                                            <line
                                                x1="18"
                                                y1="6"
                                                x2="6"
                                                y2="18"
                                            ></line>
                                            <line
                                                x1="6"
                                                y1="6"
                                                x2="18"
                                                y2="18"
                                            ></line>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-1">
                                <input
                                    disabled
                                    :placeholder="placeholder"
                                    class="bg-transparent p-1 px-2 appearance-none outline-none h-full w-full text-gray-800"
                                >
                            </div>
                        </div>
                        <div class="text-gray-300 w-8 py-1 pl-2 pr-1 border-l flex items-center border-gray-200 ">
                            <button
                                @click="isSelectOpen = !isSelectOpen"
                                class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="100%"
                                    height="100%"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="feather feather-chevron-up w-4 h-4"
                                >
                                    <path
                                        v-if="isSelectOpen"
                                        d="M6.1018 16.9814C5.02785 16.9814 4.45387 15.7165 5.16108 14.9083L10.6829 8.59762C11.3801 7.80079 12.6197 7.80079 13.3169 8.59762L18.8388 14.9083C19.5459 15.7165 18.972 16.9814 17.898 16.9814H6.1018Z"
                                        class="text-sky-500"
                                    />
                                    <path
                                        v-else
                                        d="M6.1018 8C5.02785 8 4.45387 9.2649 5.16108 10.0731L10.6829 16.3838C11.3801 17.1806 12.6197 17.1806 13.3169 16.3838L18.8388 10.0731C19.5459 9.2649 18.972 8 17.898 8H6.1018Z"
                                        class="text-sky-500"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    v-if="isSelectOpen"
                    class="absolute shadow top-100 bg-white z-40 w-full lef-0 rounded max-h-select overflow-y-auto "
                >
                    <div class="flex flex-col w-full">
                        <div
                            v-for="option in options"
                            :key="option.code"
                            class="cursor-pointer w-full border-gray-100 rounded-t border-b hover:bg-sky-100"
                        >
                            <div
                                @click="selectOption(option)"
                                class="flex w-full items-center p-2 pl-2 border-transparent border-l-2 relative hover:border-sky-100"
                            >
                                <div class="w-full items-center flex">
                                    <div class="mx-2 leading-6  ">{{ option.option }} </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isSelectOpen: false,
            selectedOptions: this.value || [],
        }
    },
    props: ['options', 'value', 'placeholder'],
    methods: {
        selectOption(option) {
            const optionIndex = this.selectedOptions.findIndex(el => el.code === option.code);
            if (optionIndex === -1) {
                this.selectedOptions.push(option)
            } else {
                this.selectedOptions.splice(optionIndex, 1);
            }
            this.$emit('input', this.selectedOptions.value);
        }
    }
}
</script>

<style>
.top-100 {
    top: 100%
}

.bottom-100 {
    bottom: 100%
}

.max-h-select {
    max-height: 300px;
}
</style>
