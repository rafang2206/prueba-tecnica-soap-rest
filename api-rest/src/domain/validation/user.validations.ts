import * as joi from 'joi';

export const userSchema = joi.object({
  document: joi.string().required(),
  name: joi.string().required(),
  email: joi.string().email().required(),
  phone: joi.number().required(),
});