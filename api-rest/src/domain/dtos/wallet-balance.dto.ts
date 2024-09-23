export class WalletBalanceDto {
  public document: string;
  public phone: number;

  constructor({ document, phone } : { document: string, phone: number }) {
    this.document = document;
    this.phone = phone;
  }
}