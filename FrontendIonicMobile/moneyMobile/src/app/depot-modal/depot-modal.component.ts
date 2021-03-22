import { CodeModalComponent } from './../code-modal/code-modal.component';
import { Router } from '@angular/router';
import { TransactionService } from './../services/transaction.service';
import { FormGroup, FormBuilder } from '@angular/forms';
import { Component, OnInit, Input } from '@angular/core';
import { ModalController } from '@ionic/angular';

@Component({
  selector: 'app-depot-modal',
  templateUrl: './depot-modal.component.html',
  styleUrls: ['./depot-modal.component.scss'],
})
export class DepotModalComponent implements OnInit {
  @Input() data:any;

  observable;
  confirmDepot : FormGroup;

  constructor(
    private modalCtrl : ModalController,
    private formBuilder : FormBuilder,
    private transactionService : TransactionService,
    private router : Router
  ) { }

  ngOnInit() {
    this.confirmDepot  =  this.formBuilder.group({
      numCniEmetteur : this.data[0]["numCniEmetteur"],
      nomCompletEmetteur : this.data[0]["nomCompletEmetteur"],
      telephoneEmetteur: this.data[0]["telephoneEmetteur"],
      nomCompletBeneficiaire : this.data[0]["nomCompletBeneficiaire"],
      telephoneBeneficiaire : this.data[0]["telephoneBeneficiaire"],
      montant : this.data[0]["montant"]
    });
  }
  async closeModal(){
    await this.modalCtrl.dismiss();
  }

  reloadComponent() {
    let currentUrl = this.router.url;
    this.router.routeReuseStrategy.shouldReuseRoute = () => false;
    this.router.onSameUrlNavigation = 'reload';
    return this.router.navigate(['/acceuil']);
  }

  refresh (){
    this.router.navigateByUrl('/depot', { skipLocationChange: true }).then(() => {
      this.router.navigate(['acceuil']);
    });
  }

  depot() {
    return this.transactionService.addDepot(this.confirmDepot.value).subscribe(
      res => {
      console.log(this.confirmDepot.value);

        console.log(res),
        this.closeModal(),
        this.showModal(res)
      }
    );
  }

  async showModal(res){
      const modal = await this.modalCtrl.create({
        component : CodeModalComponent,
        componentProps : {
          data : [res, this.data[0]["nomCompletBeneficiaire"]]
        },
        cssClass : 'my-codeModal-component-css'
      })
      await modal.present();
  }

}
