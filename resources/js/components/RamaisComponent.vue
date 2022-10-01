<template>
    <div class="container">
        <center>
            <div class="top-title">
                <img src="/img/apex-001.png" alt="">
            </div>    
        </center>
        <br>
            <div class="input-group">
                <input type="text" class="form-control" autofocus placeholder="Nome, Ramal ou Área do Colaborador" aria-label="Recipient's username" aria-describedby="basic-addon2"  v-model="search" >
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" v-on:click="card = true"><i class="fa fa-id-card"></i> Cartão</button>
                    <button class="btn btn-outline-secondary" type="button" v-on:click="card = false"><i class="fa fa-list"></i> Lista</button>
                </div>
            </div>
        <section class="transparent" id="card-view" v-if="card == true">
            <div class="container">
                <div class="row">
                
                    <div class="col-md-3 card-padding" v-for="u in filteredUsers">
                       
                        <div class="card profile-card-3">
                            <div class="background-block">
                                <img src="/img/apex-background-profile.jpg" alt="profile-sample1" class="background"/>
                            </div>
                            <div class="profile-thumb-block">
                                <img :src="u.picture" alt="profile-image" class="profile"/>
                            </div>
                            <br>
                            <div class="card-content" >

                            <h5 class="text-center">{{u.displayName}}</h5>
                            <h6 class="text-center"><span v-if="u.jobTitle !== ''">{{u.jobTitle + " em "}}</span> <span v-if="u.department !== ''">{{u.department}} </span></h6>
                            <br>
                            <b  v-if="u.officeLocation !== ''"> <i class="fa fa-map"></i> Local:</b> <span v-if="u.officeLocation !== ''">{{u.officeLocation}}</span>
                            <b v-if="u.businessPhones !== ''"> <br> <i class="fa fa-phone"></i> Ramal:</b> <span v-if="u.businessPhones !== ''">{{u.businessPhones}}</span>
                            <br>
                            <b v-if="u.userPrincipalName !== ''"> <i class="fa fa-envelope"></i> E-mail:</b> <span v-if="u.userPrincipalName !== ''">{{u.userPrincipalName}}</span>
                            </div>
                                                </div>
                    </div>    
                   
                    
                </div>
            </div>
        </section>
         <section class="transparent" id="card-view" v-if="card == false">
            <div class="container">
                <div class="row">
                
                   <table class="table" style="background: white">
                    <thead>
                        <tr>
                            <th>
                                Foto
                            </th>
                            <th>
                                Nome completo
                            </th>
                            <th>
                                Setor 
                            </th>
                            <th>
                                Cargo
                            </th>
                            <th>
                                Telefone
                            </th>
                            <th>
                                E-mail
                            </th>
                            <th>Ações</th>
                        </tr>    
                    </thead>   
                    <tbody>
                        <tr v-for="u in filteredUsers">
                            <td> <img :src="u.picture" alt="profile-image" class="profile"/></td>
                            <td> {{u.displayName}} </td>
                            <td> <span v-if="u.department !== ''">{{u.department}} - {{u.officeLocation}}</span> </td>
                            <td><span v-if="u.jobTitle !== ''">{{u.jobTitle}}</span></td>
                            <td><span v-if="businessPhones !== ''">{{u.businessPhones}}</span></td>
                            <td><span v-if="u.userPrincipalName !== ''">{{u.userPrincipalName}}</span></td>
                           
                        </tr>
                    </tbody>
                    </table>         
                    
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
       props: ['users'], // Recebendo lista de usuários
       data() {
           return {
               search: '',
               list: [],
               card: true, // quando true a exibição defaul é card, quando false a exibição default é lista.
           }
       },
       mounted () {
           // tratamento da lista de usuários recebidos como JSON via BLADE
           this.list = JSON.parse(this.users)

           // Fazendo Each em cada usuário para alterar a URL da foto e limpar valores NULL
           var userslist = this.list.map(user => {
              
               user.picture = "/GetUsersPhotoAPI/"+user.id
               if(user.businessPhones.length > 0){
                   user.businessPhones = user.businessPhones[0]
               }else{
                   user.businessPhones = null
               }
                (user.displayName) ? user.displayName : user.displayName = "";
                (user.department) ? user.department : user.department = "";
                (user.jobTitle) ? user.jobTitle : user.jobTitle = "";
                (user.userPrincipalName) ? user.userPrincipalName : user.userPrincipalName = "";
                (user.businessPhones) ? user.businessPhones : user.businessPhones = "";
                (user.officeLocation) ? user.officeLocation : user.officeLocation = "";

               return user
           })
           this.list = userslist;
       },
     computed: {
         // Toda vez que for digitado algo na barra de pesquisa e lista é filtrada automaticamente sem fazer requisição para o back-end.

        filteredUsers: function () {
            var self = this;

            // função .filter default do vue.js
            return this.list.filter(function (person) {
                // filtro por Nome, Departamento, Cargo, UPN (email), Localização e Andar. 

                return person.displayName.toLowerCase().indexOf(self.search.toLowerCase()) >= 0
                || person.department.toLowerCase().indexOf(self.search.toLowerCase()) >= 0
                || person.jobTitle.toLowerCase().indexOf(self.search.toLowerCase()) >= 0
                || person.userPrincipalName.toLowerCase().indexOf(self.search.toLowerCase()) >= 0
                || person.businessPhones.toLowerCase().indexOf(self.search.toLowerCase()) >= 0
                || person.officeLocation.toLowerCase().indexOf(self.search.toLowerCase()) >= 0
            });
        }
    }
    }
</script>
