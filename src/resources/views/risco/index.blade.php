@extends('laravel-enso/core::layouts.app')

@section('pageTitle', __("App Statistics"))

@section('css')
    <style>

    </style>
@endsection

@section('content')

    <section class="content-header">

        @include('laravel-enso/menumanager::breadcrumbs')
    </section>

    <section class="content" v-cloak >

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

    </section>

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
                            {{ __("State") }}: <strong> <span v-html="companyData.State"></span></strong><br>
                            {{ __("Start Date") }}: <span v-html="companyData.DateStart"></span><br>
                            {{ __("CUI") }}: <span v-html="companyData.FiscalCode"></span><br>
                            {{ __("County") }}: <span v-html="companyData.Judet"></span><br>
                            {{ __("City") }}: <span v-html="companyData.Localitate"></span><br>
                            {{ __("Name") }}: <span v-html="companyData.Name"></span><br>
                            {{ __("RegNo") }}: <span v-html="companyData.RegNo"></span><br>
                            {{ __("Street") }}: <span v-html="companyData.Strada"></span>

                        </address>
                    </div>

                    <div class="col-md-6">
                        <address >
                            {{ __("Caen") }}: <strong> <span v-html="caenData.Caen"></span></strong><br>
                            {{ __("Description") }}: <span v-html="caenData.Descriere"></span><br>
                            {{ __("Version") }}: <span v-html="caenData.Versiune"></span><br>

                        </address>
                    </div>
                </div>

                <hr>

                <div class="row" v-for="item in financialData">
                    <div class="col-md-6">
                        <address >
                            {{ __("Year / Month ") }}: <strong> <span v-html="item['@attributes'].An + '/' + item['@attributes'].Luna"></span></strong><br>
                            {{ __("Profit net") }}: <span v-html="item['@attributes'].F20_0672"></span><br>
                        </address>
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
                            {{ __("Name") }}: <span v-html="identificationData.nume"></span><br>
                            {{ __("State") }}: <strong> <span v-html="identificationData.stare"></span></strong><br>
                            {{ __("County") }}: <span v-html="identificationData.judet"></span><br>
                            {{ __("City") }}: <span v-html="identificationData.localitate"></span><br>
                            {{ __("Address") }}: <span v-html="identificationData.adresa"></span><br>
                            {{ __("CUI") }}: <span v-html="identificationData.codFiscal"></span><br>
                            {{ __("fax") }}: <span v-html="identificationData.fax"></span><br>
                            {{ __("dataInregistrareRecom") }}: <span v-html="identificationData.dataInregistrareRecom"></span><br>
                            {{ __("formaLegala") }}: <span v-html="identificationData.formaLegala"></span><br>
                            {{ __("dataUltimeiActualizari") }}: <span v-html="identificationData.dataUltimeiActualizari"></span><br>
                            {{ __("cifra_de_afaceri") }}: <span v-html="identificationData.cifra_de_afaceri"></span><br>
                            {{ __("administrator") }}: <span v-html="identificationData.administrator"></span><br>
                            {{ __("TVAlaIncasare") }}: <span v-html="identificationData.TVAlaIncasare"></span><br>
                            {{ __("caen") }}: <span v-html="identificationData.caen"></span><br>
                            {{ __("caen_desc") }}: <span v-html="identificationData.caen_desc"></span><br>
                            {{ __("nrInregistrareRecom") }}: <span v-html="identificationData.nrInregistrareRecom"></span><br>
                            {{ __("profit_net") }}: <span v-html="identificationData.profit_net"></span><br>
                            {{ __("tara") }}: <span v-html="identificationData.tara"></span><br>
                            {{ __("telefon") }}: <span v-html="identificationData.telefon"></span><br>
                        </address>
                    </div>
                </div>
            </div>
        </div>
    </script><script type="text/x-template" id="iid-template">

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
                            {{ __("Name") }}: <span v-html="identificationData.nume"></span><br>
                            {{ __("State") }}: <strong> <span v-html="identificationData.stare"></span></strong><br>
                            {{ __("County") }}: <span v-html="identificationData.judet"></span><br>
                            {{ __("City") }}: <span v-html="identificationData.localitate"></span><br>
                            {{ __("Address") }}: <span v-html="identificationData.adresa"></span><br>
                            {{ __("CUI") }}: <span v-html="identificationData.codFiscal"></span><br>
                            {{ __("fax") }}: <span v-html="identificationData.fax"></span><br>
                            {{ __("dataInregistrareRecom") }}: <span v-html="identificationData.dataInregistrareRecom"></span><br>
                            {{ __("formaLegala") }}: <span v-html="identificationData.formaLegala"></span><br>
                            {{ __("dataUltimeiActualizari") }}: <span v-html="identificationData.dataUltimeiActualizari"></span><br>
                            {{ __("cifra_de_afaceri") }}: <span v-html="identificationData.cifra_de_afaceri"></span><br>
                            {{ __("administrator") }}: <span v-html="identificationData.administrator"></span><br>
                            {{ __("TVAlaIncasare") }}: <span v-html="identificationData.TVAlaIncasare"></span><br>
                            {{ __("caen") }}: <span v-html="identificationData.caen"></span><br>
                            {{ __("caen_desc") }}: <span v-html="identificationData.caen_desc"></span><br>
                            {{ __("nrInregistrareRecom") }}: <span v-html="identificationData.nrInregistrareRecom"></span><br>
                            {{ __("profit_net") }}: <span v-html="identificationData.profit_net"></span><br>
                            {{ __("tara") }}: <span v-html="identificationData.tara"></span><br>
                            {{ __("telefon") }}: <span v-html="identificationData.telefon"></span><br>
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
                            {{ __("Name") }}: <span v-html="statusData.nume"></span><br>
                            {{ __("Stare") }}: <strong> <span v-html="statusData.stare"></span></strong><br>
                            {{ __("Status") }}: <span v-html="statusData.status"></span><br>
                            {{ __("CUI") }}: <span v-html="statusData.codFiscal"></span><br>
                            {{ __("dataUltimeiActualizari") }}: <span v-html="statusData.dataUltimeiActualizari"></span><br>
                            {{ __("TVAlaIncasare") }}: <span v-html="statusData.TVAlaIncasare"></span><br>
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
                        self.companyInfo = response.data.Financial_Res;
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
                        companyData: function () {
                            return this.details.RawData.CompanyData['@attributes'];
                        },
                        caenData: function () {
                            return this.details.RawData.CompanyData.Caen['@attributes'];
                        },
                        financialData: function () {
                            return this.details.RawData.CompanyData.Financial;
                        }
                    }
                },
                iidInfo: {
                    template: '#iid-template',
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
                        identificationData: function () {
                            return this.details.RawData.dateIdentificareFirma;
                        },
                    }
                },
                stsInfo: {
                    template: '#sts-template',
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
                        statusData: function () {
                            return this.details.RawData.dateIdentificareFirma;
                        },
                    }
                }
            }
        });
    </script>
@endpush