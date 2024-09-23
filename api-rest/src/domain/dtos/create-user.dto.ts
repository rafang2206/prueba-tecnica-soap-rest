
export class CreateUserDto {
  public document: string;
  public name: string;
  public email: string;
  public phone: number;

  constructor({ document, name, email, phone } : { document: string, name: string, email: string, phone: number }) {
    this.document = document;
    this.name = name;
    this.email = email;
    this.phone = phone;
  }
}