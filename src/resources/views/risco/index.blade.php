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

                    <fin-info
                            :i18n="i18n"
                            :details="companyInfo.FIN_Res"
                            v-if="companyInfo.FIN_Res">
                    </fin-info>

                    <iid-info
                            :i18n="i18n"
                            :details="companyInfo.IID_Res"
                            v-if="companyInfo.IID_Res">
                    </iid-info>

                    <sts-info
                            :i18n="i18n"
                            :details="companyInfo.STS_Res"
                            v-if="companyInfo.STS_Res">
                    </sts-info>

                </div>
            </div>
        </div>
    </page>

@endsection

@push('scripts')

    <script type="text/javascript">
        let vm = new Vue({
            el: "#app",
            data: function() {
                return {
                    query: null,
                    cui: 22197648,
                    inputHasError: false,
                    companyInfo: {},
                    i18n: {},
                    translationKeys: [
                        "Status Data","Identification Data","Financial Data","Year / Month"
                    ]
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
                    axios.get('/risco/query', { params: payload }).then(
                        (response) => {
                            self.companyInfo = response.data;
                        },
                        (error) => {
                            console.log(error.response);
                            toastr['error'](error.response.data.message);
                        }
                    );
                },
                reset: function () {
                    this.cui = null;
                },
                getTranslations: function () {
                    let self = this;
                    let payload = {
                        params: this.translationKeys
                    };
                    axios.get('/home/getTranslations', payload).then(function (response) {

                        self.i18n = response.data;
                    });
                },

            },
            mounted: function() {
                this.getTranslations();
            }
        });
    </script>
@endpush