import { TransactionService } from './../services/transaction.service';
import { ModalController } from '@ionic/angular';
import { Router } from '@angular/router';
import { FormGroup, FormBuilder } from '@angular/forms';
import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'app-calculator-modal',
  templateUrl: './calculator-modal.component.html',
  styleUrls: ['./calculator-modal.component.scss'],
})
export class CalculatorModalComponent implements OnInit {


  @Input() data : any;

  fraisForm : FormGroup;

  montant;
  frais;

  constructor(private router : Router,
    private modalCtrl : ModalController,
    private formBuilder : FormBuilder,
    private transactionService : TransactionService) { }

  ngOnInit() {
    this.fraisForm  =  this.formBuilder.group({
      montant : this.data["montant"]
    });
    this.calculer();
  }

  async closeModal(){
    this.router.navigate(['/acceuil']);
    await this.modalCtrl.dismiss();
  }


  calculer(){
    return this.transactionService.getFraisMontant(this.data["montant"]).subscribe(
      (fraisData) => {
        this.frais = fraisData,
        this.montant = this.data["montant"],
        console.log(fraisData)
      }
    );
  }
}
