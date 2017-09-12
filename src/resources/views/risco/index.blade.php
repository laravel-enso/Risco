@extends('laravel-enso/core::layouts.app')

@section('pageTitle', __("App Statistics"))

@section('css')
    <style>

    </style>
@endsection

@section('content')

    <page v-cloak>

        <div class="col-md-12">

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary" v-cloak>
                        <div class="box-body" style="">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group" :class="{'has-error' : inputHasError}">
                                        <input type="text" class="form-control"
                                               placeholder="CUI"
                                               v-model="cui"
                                               @keydown.enter= "fetch"
                                               @keydown.esc="reset"
                                        >
                                        <span class="input-group-addon">
                                            <i class="fa fa-search"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-8">

                                    <button class="btn btn-primary"
                                            @click="requestFIN"
                                            v-show="cuiIsValid"
                                    >
                                        {{ __("FIN") }}
                                    </button>

                                    <button class="btn btn-primary"
                                            @click="requestIID"
                                            v-show="cuiIsValid"
                                    >
                                        {{ __("IID") }}
                                    </button>

                                    <button class="btn btn-primary"
                                            @click="requestSTS"
                                            v-show="cuiIsValid"
                                    >
                                        {{ __("STS") }}
                                    </button>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">

                    <fin-info :details="companyInfo.FIN_Res" v-if="companyInfo.FIN_Res">
                    </fin-info>

                    <iid-info :details="companyInfo.IID_Res" v-if="companyInfo.IID_Res">
                    </iid-info>

                    <sts-info :details="companyInfo.STS_Res" v-if="companyInfo.STS_Res">
                    </sts-info>

                </div>
            </div>
        </div>
    </page>

@endsection

@push('scripts')

    <!-- templates -->
    <script type="text/x-template" id="fin-template">

        <div class="box box-primary" v-cloak>

            <div class="box-header">
                <div class="box-title">
                    {{(__("Financial Data"))}}

                </div>
            </div>

            <div class="box-body" style="">

                <div class="row">
                    <div class="col-md-6">
                        <address >

                            <div v-for="item in details.companyData ">
                                <strong><span v-html="item.key"></span></strong> <span v-html="item.value"></span><br>
                            </div>

                        </address>
                    </div>

                    <div class="col-md-6">
                        <address >
                            <div v-for="item in details.caenData ">
                                <strong><span v-html="item.key"></span></strong> <span v-html="item.value"></span><br>
                            </div>

                        </address>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <div class="row">

                            <div class="col-md-3" v-for="item in details.financialData">
                                <address >
                                    {{ __("Year / Month ") }}: <strong> <span v-html="item.year + '/' + item.month"></span></strong><br>

                                    <div v-for="subitem in item.details ">
                                        <strong><span v-html="subitem.key"></span></strong> <span v-html="subitem.value"></span><br>
                                    </div>

                                </address>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="iid-template">

        <div class="box box-primary" v-cloak>

            <div class="box-header">
                <div class="box-title">
                    {{(__("Identification Data"))}}

                </div>
            </div>

            <div class="box-body" style="">

                <div class="row">
                    <div class="col-md-6">
                        <address >
                            <div v-for="item in details">
                                <strong><span v-html="item.key"></span></strong> <span v-html="item.value"></span><br>
                            </div>
                        </address>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="sts-template">

        <div class="box box-primary" v-cloak>

            <div class="box-header">
                <div class="box-title">
                    {{(__("Status Data"))}}

                </div>
            </div>

            <div class="box-body" style="">

                <div class="row">
                    <div class="col-md-6">
                        <address >
                            <div v-for="item in details">
                                <strong><span v-html="item.key"></span></strong> <span v-html="item.value"></span><br>
                            </div>
                        </address>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/javascript">
        let vm = new Vue({
            el: "#app",
            data: function() {
                return {
                    query: null,
                    cui: 22197648,
                    inputHasError: false,
                    companyInfo: {}
                }
            },
            computed: {
                cuiIsValid: function () {
                    return this.cui !== null &&
                        this.cui !== '' &&
                        typeof this.cui !== typeof undefined;
                }
            },
            methods: {
                makePayload: function (fin=0, iid=0, sts=0) {

                    return {
                        fin: fin,
                        iid: iid,
                        sts: sts,
                        cui: this.cui
                    };
                },
                requestSTS: function () {

                    let payload = this.makePayload(0,0,1);
                    this.fetch(payload);
                },
                requestFIN: function () {

                    let payload = this.makePayload(1,0,0);
                    this.fetch(payload);
                },
                requestIID: function () {

                    let payload = this.makePayload(0,1,0);
                    this.fetch(payload);
                },
                fetch: function (payload) {

                    let self = this;
                    axios.get('/risco/query', { params: payload }).then((response) => {
                        self.companyInfo = response.data;
                    });
                },
                reset: function () {
                    this.cui = null;
                }
            },
            created: function() {
            },
            components: {

                finInfo: {
                    template: '#fin-template',
                    props: {
                        details: {
                            type: Object,
                            default: function () {
                                return {
                                    a: 0
                                };
                            }
                        }
                    },
                    computed: {

                    }
                },
                iidInfo: {
                    template: '#iid-template',
                    props: {
                        details: {
                            type: Array,
                            default: function () {
                                return [];
                            }
                        }
                    }
                },
                stsInfo: {
                    template: '#sts-template',
                    props: {
                        details: {
                            type: Array,
                            default: function () {
                                return [];
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush