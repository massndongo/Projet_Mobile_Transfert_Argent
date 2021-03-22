import { RetraitModalComponent } from './../retrait-modal/retrait-modal.component';
import { ModalController } from '@ionic/angular';
import { TransactionService } from './../services/transaction.service';
import { Router } from '@angular/router';
import { FormGroup, FormControl, Validators, FormBuilder } from '@angular/forms';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-retrait-formulaire',
  templateUrl: './retrait-formulaire.component.html',
  styleUrls: ['./retrait-formulaire.component.scss'],
})
export class RetraitFormulaireComponent implements OnInit {

  disabledEmetteurInputs = false;
  disabledBeneficiaireInputs = true;
  disabled = true;

  retraitForm : FormGroup;
  codeFormControl = new FormControl('', [Validators.required]);
  nomCompletBeneficiaireFormControl = new FormControl();
  cniBeneficiaireFormControl = new FormControl('', [Validators.required]);
  telephoneBeneficiaireFormControl = new FormControl();
  montantFormControl = new FormControl();
  nomCompletEmetteurFormControl = new FormControl();
  telephoneEmetteurFormControl = new FormControl();

  transInfo = [];
  clientBeneficiaire = [];
  clientEmetteur = [];

  constructor(private router : Router, private transactionService : TransactionService, private modalCtrl : ModalController,private formBuilder : FormBuilder) { }

  ngOnInit() {
    this.retraitForm = this.formBuilder.group({
      codeTrans : this.codeFormControl,
      numCni : this.cniBeneficiaireFormControl,
      nomBeneficiaire : this.nomCompletBeneficiaireFormControl,
      telephone : this.telephoneBeneficiaireFormControl,
      montant : this.montantFormControl
    });
  }

  getBackHome(){
    return this.router.navigateByUrl('accueil');
  }

  disabledBeneficiaire(){
    if (this.disabledEmetteurInputs == false){
      this.disabledEmetteurInputs = true;
    }
    if (this.disabledBeneficiaireInputs == true){
      this.disabledBeneficiaireInputs = false;
    }
  }

  disabledEmetteur(){
    if (this.disabledEmetteurInputs == true){
      this.disabledEmetteurInputs = false;
    }
    if (this.disabledBeneficiaireInputs == false){
      this.disabledBeneficiaireInputs = true;
    }
  }

  getTransByCode(){
    return this.transactionService.getTransactionByCode(this.codeFormControl.value).subscribe(
      (res:any) => {
        console.log(res);
        this.transInfo = res;
        this.clientBeneficiaire = res["clientRetrait"];
        this.clientEmetteur = res["clientDepot"];
      }
    )
  }

  reloadComponent() {
    let currentUrl = this.router.url;
    this.router.routeReuseStrategy.shouldReuseRoute = () => false;
    this.router.onSameUrlNavigation = 'reload';
    return this.router.navigateByUrl('/acceuil');
  }

  refresh (){
    this.router.navigateByUrl('/depot', { skipLocationChange: true }).then(() => {
      this.router.navigate(['acceuil']);
    });
  }

  async showModal(){
    if(this.retraitForm.valid){
      const modal = await this.modalCtrl.create({
        component : RetraitModalComponent,
        componentProps : {
          data : [this.retraitForm.value, this.clientEmetteur["nomComplet"], this.clientEmetteur["telephone"]]
        },
        cssClass : 'my-modal-component-css'
      })
      await modal.present();
    }
  }
}
