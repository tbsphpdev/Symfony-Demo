<?php

namespace Chartwell\Modules\ClientAndSiteManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Chartwell\Modules\ClientAndSiteManagementBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="Chartwell\Modules\ClientAndSiteManagementBundle\Entity\ProjectRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable(logEntryClass="Chartwell\SecurityBundle\Entity\LogChangeEntry")
 */
class Project
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="sites")
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id")
     */
    private $siteId;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @var string
     *
     * @ORM\Column(name="contract_number", type="string", length=255)
     */
    private $contractNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="order_number", type="string", length=255, nullable = true)
     */
    private $orderNumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="contract_start_date", type="date", nullable = true)
     */
    private $contractStartDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="contract_end_date", type="date", nullable = true)
     */
    private $contractEndDate;

    /**
     * @var string
     *
     * @ORM\Column(name="project_value", type="decimal", precision=10, scale=2, nullable=true, options={"default"= 0.00})
     */
    private $projectValue;

    /**
     * @var string
     *
     * @ORM\Column(name="contract_type", type="string", length=255, nullable = true)
     */
    private $contractType;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_type", type="string", length=255, nullable = true)
     */

    private $paymentType;
    /**
     * @var integer
     *
     * @ORM\Column(name="contract_number_of_days", type="integer", nullable = true)
     */
    private $contractNumberOfDays;

    /**
     * @var string
     *
     * @ORM\Column(name="additional_information", type="text", nullable = true)
     */
    private $additionalInformation;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(name="is_deleted", type="boolean", options={"default"=0}, nullable=false)
     */
    private $isDeleted = 0;

    /**
     * @ORM\Column(name="payment_terms", type="string", length=255, nullable = true)
     */
    private $paymentTerms;

    /**
     * @ORM\Column(name="retention_period", type="string", length=255, nullable = true)
     */
    private $retentionPeriod;

    /**
     * @ORM\Column(name="retention_percentage", type="string", length=255, nullable = true)
     */
    private $retentionPercentage;

    /**
     * @ORM\Column(name="project_manager", type="string", length=255, nullable = true)
     */
    private $projectManager;

    /**
     * @ORM\Column(name="supervisor", type="string", length=255, nullable = true)
     */
    private $supervisor;


    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAtValue() {
        $this->createdAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function setUpdatedAtValue() {
        $this->updatedAt = new \DateTime();
    }

    public function toArray() {

        return array(
            'Id' => $this->getId(),
            'Date created' => !is_null($this->getSiteId()->getCreatedAt()) ? $this->getSiteId()->getCreatedAt()->format('d/m/Y') : '',
            'Contract Number' => $this->getContractNumber(),
            'Site Name' => $this->getSiteId()->getSiteName(),
            'Client' => $this->getClient()->getName(),
            'Address' => $this->getSiteId()->getAddress(),
            'Town' => $this->getSiteId()->getTown(),
            'Post Code' => $this->getSiteId()->getPostCode(),
            'Order Number' => $this->getOrderNumber(),
            'Start Date' => !is_null($this->getContractStartDate()) ? $this->getContractStartDate()->format('d/m/Y'): '',
            'End Date' => !is_null($this->getContractEndDate()) ? $this->getContractEndDate()->format('d/m/Y') : '',
            'Number of days' => $this->getContractNumberOfDays(),
            'Value' => $this->getProjectValue(),
            'Contract Type' => "'" . $this->getContractType() . "'", //to be not reads as a date
            'Additional Information' => $this->getAdditionalInformation(),
            'Payment Terms' => $this->getPaymentTerms(),
            'Retention Period' => $this->getRetentionPeriod(),
            'Retention Percentage' => $this->getRetentionPercentage(),
            'Project Manager' => $this->getProjectManager(),
            'Supervisor' => $this->getSupervisor(),
            'Payment Type' => $this->getPaymentType()
        );
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set siteId
     *
     * @param integer $siteId
     * @return Contract
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     * Get siteId
     *
     * @return integer 
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * Set contractNumber
     *
     * @param string $contractNumber
     * @return Contract
     */
    public function setContractNumber($contractNumber)
    {
        $this->contractNumber = $contractNumber;

        return $this;
    }

    /**
     * Get contractNumber
     *
     * @return string 
     */
    public function getContractNumber()
    {
        return $this->contractNumber;
    }

    /**
     * Set orderNumber
     *
     * @param string $orderNumber
     * @return Contract
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * Get orderNumber
     *
     * @return string 
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * Set contractStartDate
     *
     * @param \DateTime $contractStartDate
     * @return Contract
     */
    public function setContractStartDate($contractStartDate)
    {
        $this->contractStartDate = $contractStartDate;

        return $this;
    }

    /**
     * Get contractStartDate
     *
     * @return \DateTime 
     */
    public function getContractStartDate()
    {
        return $this->contractStartDate;
    }

    /**
     * Set contractEndDate
     *
     * @param \DateTime $contractEndDate
     * @return Contract
     */
    public function setContractEndDate($contractEndDate)
    {
        $this->contractEndDate = $contractEndDate;

        return $this;
    }

    /**
     * Get contractEndDate
     *
     * @return \DateTime 
     */
    public function getContractEndDate()
    {
        return $this->contractEndDate;
    }

    /**
     * Set projectValue
     *
     * @param string $contractValue
     * @return Contract
     */
    public function setProjectValue($projectValue)
    {
        $this->projectValue = $projectValue;

        return $this;
    }

    /**
     * Get contractValue
     *
     * @return string 
     */
    public function getProjectValue()
    {
        return $this->projectValue;
    }

    /**
     * Set paymentType
     *
     * @param string $paymentType
     * @return Project
     */

    public  function  setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * Get paymentType
     * @return string
     */

    public  function  getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Set contractType
     *
     * @param string $contractType
     * @return Contract
     */
    public function setContractType($contractType)
    {
        $this->contractType = $contractType;

        return $this;
    }

    /**
     * Get contractType
     *
     * @return string 
     */
    public function getContractType()
    {
        return $this->contractType;
    }

    /**
     * Set contractNumberOfDays
     *
     * @param integer $contractNumberOfDays
     * @return Contract
     */
    public function setContractNumberOfDays($contractNumberOfDays)
    {
        $this->contractNumberOfDays = $contractNumberOfDays;

        return $this;
    }

    /**
     * Get contractNumberOfDays
     *
     * @return integer 
     */
    public function getContractNumberOfDays()
    {
        return $this->contractNumberOfDays;
    }

    /**
     * Set additionalInformation
     *
     * @param string $additionalInformation
     * @return Contract
     */
    public function setAdditionalInformation($additionalInformation)
    {
        $this->additionalInformation = $additionalInformation;

        return $this;
    }

    /**
     * Get additionalInformation
     *
     * @return string 
     */
    public function getAdditionalInformation()
    {
        return $this->additionalInformation;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Contract
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Contract
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     * @return User
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set client
     *
     * @param \Chartwell\Modules\ClientAndSiteManagementBundle\Entity\Client $client
     * @return Site
     */
    public function setClient(\Chartwell\Modules\ClientAndSiteManagementBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \Chartwell\Modules\ClientAndSiteManagementBundle\Entity\Client
     */

    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set paymentType
     *
     * @param string $paymentTerms
     * @return Project
     */

    public  function  setPaymentTerms($paymentTerms)
    {
        $this->paymentTerms = $paymentTerms;

        return $this;
    }

    /**
     * Get paymentTerms
     * @return string
     */

    public  function  getPaymentTerms()
    {
        return $this->paymentTerms;
    }

    /**
     * Set retentionPeriod
     *
     * @param string $retentionPeriod
     * @return Project
     */

    public  function  setRetentionPeriod($retentionPeriod)
    {
        $this->retentionPeriod = $retentionPeriod;

        return $this;
    }

    /**
     * Get retentionPeriod
     * @return string
     */

    public  function  getRetentionPeriod()
    {
        return $this->retentionPeriod;
    }

    /**
     * Set retentionPercentage
     *
     * @param string $retentionPercentage
     * @return Project
     */

    public  function  setRetentionPercentage($retentionPercentage)
    {
        $this->retentionPercentage = $retentionPercentage;

        return $this;
    }

    /**
     * Get retentionPercentage
     * @return string
     */

    public  function  getRetentionPercentage()
    {
        return $this->retentionPercentage;
    }

    /**
     * Set projectManager
     *
     * @param string $projectManager
     * @return Project
     */

    public  function  setProjectManager($projectManager)
    {
        $this->projectManager = $projectManager;

        return $this;
    }

    /**
     * Get projectManager
     * @return string
     */

    public  function  getProjectManager()
    {
        return $this->projectManager;
    }

    /**
     * Set supervisor
     *
     * @param string $supervisor
     * @return Project
     */

    public  function  setSupervisor($supervisor)
    {
        $this->supervisor = $supervisor;

        return $this;
    }

    /**
     * Get supervisor
     * @return string
     */

    public  function  getSupervisor()
    {
        return $this->supervisor;
    }



    public function getSitesAvailable()
    {
        return 1;
    }


}
