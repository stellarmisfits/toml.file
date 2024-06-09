import axios from 'axios'
import { fetchLinkedOrgs, fetchUnlinkedOrgs } from '~/store/linked-orgs'

export const state = {
  validators: [],
  linkedOrgs: [],
  unlinkedOrgs: []
}

// getters
export const getters = {
  getValidatorByUuid: (state) => (uuid) => {
    return state.validators.find(validator => validator.uuid === uuid)
  },
  validators: state => (state.validators.length) ? state.validators : null,
  linkedOrgs: state => state.linkedOrgs,
  unlinkedOrgs: state => state.unlinkedOrgs
}

// actions
export const actions = {
  async fetchValidators ({ commit }) {
    const { data } = await axios.get('/api/validators')
    commit('SET_VALIDATORS', { validators: data.data })
  },

  async fetchValidator ({ commit, getters }, uuid) {
    let validator = getters.getValidatorByUuid(uuid)

    if (!validator) {
      const { data } = await axios.get('/api/validators/' + uuid)
      commit('SET_VALIDATOR', { validator: data.data })
    }
  },

  fetchLinkedOrgs,
  fetchUnlinkedOrgs
}

// mutations
export const mutations = {
  SET_VALIDATORS (state, { validators }) {
    state.validators = validators
  },
  SET_VALIDATOR (state, { validator }) {
    state.validators.push(validator)
  },
  SET_LINKED_ORGS (state, { orgs }) {
    state.linkedOrgs = orgs
  },
  SET_UNLINKED_ORGS (state, { orgs }) {
    state.unlinkedOrgs = orgs
  }
}
