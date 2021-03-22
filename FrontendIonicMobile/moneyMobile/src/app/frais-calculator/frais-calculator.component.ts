import { ModalController } from '@ionic/angular';
import { TransactionService } from './../services/transaction.service';
import { Router } from '@angular/router';
import { FormGroup, FormControl, Validators, FormBuilder } from '@angular/forms';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-frais-calculator',
  templateUrl: './frais-calculator.component.html',
  styleUrls: ['./frais-calculator.component.scss'],
})
export class FraisCalculatorComponent implements OnInit {



  calculatorForm : FormGroup;
  montantFormControl = new FormControl('', [Validators.required, Validators.pattern(/(^[0-9])/)]);

  frais : number = 0;
  montant : number;

  constructor(private router : Router, private formBuilder: FormBuilder, private transactionService : TransactionService, private modalCtrl : ModalController) { }

  ngOnInit() {
    this.calculatorForm  =  this.formBuilder.group({
      montant : this.montantFormControl
    });
  }

  calculator(){
    console.log(this.montantFormControl.value);

    return this.transactionService.getFraisMontant(this.montantFormControl.value).subscribe(
      (fraisData : number) => {
        this.frais = fraisData
      }
    );
  }

  getBackHome(){
    return this.router.navigate(['/acceuil']);
  }
}
