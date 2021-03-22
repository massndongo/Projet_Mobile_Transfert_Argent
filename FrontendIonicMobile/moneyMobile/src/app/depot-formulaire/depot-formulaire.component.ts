import { ClientService } from './../services/client.service';
import { DepotModalComponent } from './../depot-modal/depot-modal.component';
import { TransactionService } from './../services/transaction.service';
import { Router } from '@angular/router';
import { FormGroup, FormControl, Validators, FormBuilder } from '@angular/forms';
import { Component, OnInit } from '@angular/core';
import { ModalController } from '@ionic/angular';

@Component({
  selector: 'app-depot-formulaire',
  templateUrl: './depot-formulaire.component.html',
  styleUrls: ['./depot-formulaire.component.scss'],
})
export class DepotFormulaireComponent implements OnInit {
  disabledEmetteurInputs = true;
  disabledBeneficiaireInputs = false;
  hiddenForModal = true;
  hiddenForNext = false;
  disabled = false;
  clientEmetteur: any;

  frais = 0;
  total = 0;

  depotForm: FormGroup;
  cniEmetteurFormControl = new FormControl('', [Validators.required]);
  nomCompletEmetteurFormControl = new FormControl('', [Validators.required]);
  telephoneEmetteurFormControl = new FormControl('', [Validators.required]);
  nomCompletBeneficiaireFormControl = new FormControl('', [Validators.required]);
  telephoneBeneficiaireFormControl = new FormControl('', [Validators.required]);
  montantFormControl = new FormControl('', [Validators.required]);

  constructor(
    private modalCtrl : ModalController,
    private formBuilder: FormBuilder,
    private router: Router,
    private transactionService : TransactionService,
    private clientService: ClientService
  ) { }

  ngOnInit() {
    this.disabledEmetteurInputs = false;
    this.disabledBeneficiaireInputs = true;
    this.depotForm  =  this.formBuilder.group({
      numCniEmetteur : this.cniEmetteurFormControl,
      nomCompletEmetteur : this.nomCompletEmetteurFormControl,
      telephoneEmetteur: this.telephoneEmetteurFormControl,
      nomCompletBeneficiaire : this.nomCompletBeneficiaireFormControl,
      telephoneBeneficiaire : this.telephoneBeneficiaireFormControl,
      montant : this.montantFormControl,
    });
  }
  getBackHome(){
    return this.router.navigateByUrl('accueil');
  }

  disabledBeneficiaire(){
    this.disabledEmetteurInputs = false;
    this.disabledBeneficiaireInputs = true;
    // this.disabled = true;
    this.hiddenForModal = true;
    this.hiddenForNext = false;
    console.log(this.disabledEmetteurInputs);

  }

  disabledEmetteur(){
    this.disabledBeneficiaireInputs = false;
    this.disabledEmetteurInputs = true;
    this.hiddenForModal = false;
    this.hiddenForNext = true;
    if(this.depotForm.valid){
      this.disabled = false;
    }else
      if(this.depotForm.invalid){
        this.disabled = true;
    }
  }

  fraisCalculator (){
    var montant = +this.montantFormControl.value;
    console.log(montant);
    return this.transactionService.getFraisMontant(montant).subscribe(
      (dat : number) => {
        this.frais = dat,
        console.log(dat);

        console.log(this.frais);
        this.totalToGive(+this.frais, montant)
      },
      erreur => {
        console.log(erreur);

      }
    );
  }

  totalToGive(frais: number, montant: number){
    return this.transactionService.getTotal(frais, montant).subscribe(
      (data : number) => {
        this.total = data
        console.log(data);

      }
    );
  }

  nextPage(){
    this.disabledBeneficiaireInputs = false;
    this.disabledEmetteurInputs = true;
    this.hiddenForNext = true;
    this.hiddenForModal = false;
    if(this.depotForm.valid){
      this.disabled = false;
    }else
      if(this.depotForm.invalid){
        this.disabled = true;
    }
  }

  async showModal(){
    if (this.depotForm.valid){
      this.disabled = false;
      const modal = await this.modalCtrl.create({
        component : DepotModalComponent,
        componentProps : {
          data : [this.depotForm.value]
        },
        cssClass : 'my-modal-component-css'
      })
      await modal.present();
    }else{
      this.disabled = true;
    }
  }
  getClient(){
    return this.clientService.getClientByCni(this.cniEmetteurFormControl.value).subscribe(
      (res:any) => {
        console.log(res),
        this.clientEmetteur = res
      }
    )
  }

}
